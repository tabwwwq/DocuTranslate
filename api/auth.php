<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$action = $_GET['action'] ?? '';

if ($action === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $language = trim($data['language'] ?? 'Russian');

    if (!$name || !$email || !$password) {
        jsonResponse(['error' => 'All fields are required'], 400);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['error' => 'Invalid email address'], 400);
    }
    if (strlen($password) < 6) {
        jsonResponse(['error' => 'Password must be at least 6 characters'], 400);
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Email already registered'], 409);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (name, email, password, native_language) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hash, $language]);

    $userId = $db->lastInsertId();
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_language'] = $language;

    jsonResponse(['success' => true, 'name' => $name, 'language' => $language]);

} elseif ($action === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if (!$email || !$password) {
        jsonResponse(['error' => 'Email and password required'], 400);
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT id, name, password, native_language FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(['error' => 'Invalid email or password'], 401);
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_language'] = $user['native_language'];

    jsonResponse(['success' => true, 'name' => $user['name'], 'language' => $user['native_language']]);

} elseif ($action === 'update_language') {
    requireAuth();

    $data = json_decode(file_get_contents('php://input'), true);
    $language = trim((string) ($data['language'] ?? ''));

    if ($language === '') {
        jsonResponse(['error' => 'Language is required'], 400);
    }

    $db = getDB();
    $stmt = $db->prepare("UPDATE users SET native_language = ? WHERE id = ?");
    $stmt->execute([$language, $_SESSION['user_id']]);

    $_SESSION['user_language'] = $language;

    jsonResponse([
        'success' => true,
        'name' => $_SESSION['user_name'],
        'language' => $language
    ]);

} elseif ($action === 'logout') {
    session_destroy();
    jsonResponse(['success' => true]);

} elseif ($action === 'check') {
    if (isset($_SESSION['user_id'])) {
        jsonResponse(['loggedIn' => true, 'name' => $_SESSION['user_name'], 'language' => $_SESSION['user_language']]);
    } else {
        jsonResponse(['loggedIn' => false]);
    }
} else {
    jsonResponse(['error' => 'Unknown action'], 400);
}
