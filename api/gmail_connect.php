<?php
require_once '../config.php';
requireAuth();

if (GOOGLE_CLIENT_ID === '' || GOOGLE_CLIENT_SECRET === '') {
    die('Google OAuth is not configured. Set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in your environment.');
}

$state = bin2hex(random_bytes(16));
$_SESSION['gmail_oauth_state'] = $state;

$params = [
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => getGoogleRedirectUri(),
    'response_type' => 'code',
    'scope' => 'https://www.googleapis.com/auth/gmail.readonly https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
    'access_type' => 'offline',
    'prompt' => 'consent',
    'include_granted_scopes' => 'true',
    'state' => $state
];

header('Location: ' . GOOGLE_AUTH_URL . '?' . http_build_query($params));
exit;
