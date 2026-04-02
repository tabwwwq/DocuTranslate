<?php
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?gmail=login_required');
    exit;
}

if (isset($_GET['error'])) {
    header('Location: ../index.php?gmail=denied');
    exit;
}

$state = $_GET['state'] ?? '';
if (!$state || !isset($_SESSION['gmail_oauth_state']) || !hash_equals($_SESSION['gmail_oauth_state'], $state)) {
    header('Location: ../index.php?gmail=state_error');
    exit;
}
unset($_SESSION['gmail_oauth_state']);

$code = $_GET['code'] ?? '';
if ($code === '') {
    header('Location: ../index.php?gmail=code_error');
    exit;
}

$postFields = http_build_query([
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => getGoogleRedirectUri(),
    'grant_type' => 'authorization_code'
]);

$ch = curl_init(GOOGLE_TOKEN_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
$response = curl_exec($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode((string) $response, true);
if ($httpCode < 200 || $httpCode >= 300 || empty($data['access_token'])) {
    header('Location: ../index.php?gmail=token_error');
    exit;
}

$ch = curl_init(GOOGLE_USERINFO_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $data['access_token']]);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
$userInfoResponse = curl_exec($ch);
$userInfoCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$userInfo = json_decode((string) $userInfoResponse, true);

if ($userInfoCode < 200 || $userInfoCode >= 300 || empty($userInfo['email'])) {
    header('Location: ../index.php?gmail=userinfo_error');
    exit;
}

$db = getDB();
$stmt = $db->prepare('SELECT id FROM gmail_accounts WHERE user_id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);
$expiresAt = date('Y-m-d H:i:s', time() + max(60, (int) ($data['expires_in'] ?? 3600) - 60));

if ($existing) {
    $stmt = $db->prepare('UPDATE gmail_accounts SET google_email = ?, google_sub = ?, access_token = ?, refresh_token = ?, expires_at = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([
        $userInfo['email'],
        $userInfo['id'] ?? '',
        $data['access_token'],
        $data['refresh_token'] ?? null,
        $expiresAt,
        $existing['id']
    ]);
} else {
    $stmt = $db->prepare('INSERT INTO gmail_accounts (user_id, google_email, google_sub, access_token, refresh_token, expires_at) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $_SESSION['user_id'],
        $userInfo['email'],
        $userInfo['id'] ?? '',
        $data['access_token'],
        $data['refresh_token'] ?? null,
        $expiresAt
    ]);
}

header('Location: ../index.php?gmail=connected');
exit;
