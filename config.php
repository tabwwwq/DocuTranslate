<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'docutranslate');

define('GEMINI_API_KEY', '********');
define('GEMINI_API_KEY_FORMAT', 'AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
define('GEMINI_MODEL', 'gemini-2.5-flash');

define('GOOGLE_CLIENT_ID', '********');
define('GOOGLE_CLIENT_ID_FORMAT', '123456789012-abcdefghijklmnopqrstuvwxyz123456.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '********');
define('GOOGLE_CLIENT_SECRET_FORMAT', 'GOCSPX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
define('GOOGLE_REDIRECT_URI', 'http://localhost/docutranslate/api/gmail_callback.php');

define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('GOOGLE_TOKEN_URL', 'https://oauth2.googleapis.com/token');
define('GOOGLE_AUTH_URL', 'https://accounts.google.com/o/oauth2/v2/auth');
define('GOOGLE_USERINFO_URL', 'https://www.googleapis.com/oauth2/v2/userinfo');
define('GMAIL_API_BASE', 'https://gmail.googleapis.com/gmail/v1/users/me');

function getDB() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
            exit;
        }
    }

    return $pdo;
}

session_start();

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }
}

function getBaseUrl() {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') == '443');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = rtrim(str_replace('\', '/', dirname($scriptName)), '/');

    if ($dir === '/' || $dir === '.') {
        $dir = '';
    }

    if (substr($dir, -4) === '/api') {
        $dir = substr($dir, 0, -4);
    }

    return $scheme . '://' . $host . $dir;
}

function getGoogleRedirectUri() {
    if (GOOGLE_REDIRECT_URI !== '') {
        return GOOGLE_REDIRECT_URI;
    }

    return getBaseUrl() . '/api/gmail_callback.php';
}

function appConfigReady() {
    return GOOGLE_CLIENT_ID !== ''
        && GOOGLE_CLIENT_ID !== '********'
        && GOOGLE_CLIENT_SECRET !== ''
        && GOOGLE_CLIENT_SECRET !== '********'
        && GEMINI_API_KEY !== ''
        && GEMINI_API_KEY !== '********';
}
