<?php
require_once '../config.php';
require_once 'analyze_shared.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

requireAuth();

$action = $_GET['action'] ?? '';

if ($action === 'upload') {
    if (!isset($_FILES['file'])) {
        jsonResponse(['error' => 'No file uploaded'], 400);
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    $file = $_FILES['file'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    $mimeType = mime_content_type($file['tmp_name']);

    if (!in_array($mimeType, $allowedTypes, true)) {
        jsonResponse(['error' => 'Only JPG, PNG, and PDF files are allowed'], 400);
    }

    if ($file['size'] > 20 * 1024 * 1024) {
        jsonResponse(['error' => 'File size must be under 20MB'], 400);
    }

    $ext = strtolower((string) pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('doc_', true) . '.' . $ext;
    $filepath = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        jsonResponse(['error' => 'Failed to save file'], 500);
    }

    jsonResponse([
        'success' => true,
        'filename' => $filename,
        'original_name' => $file['name'],
        'mime_type' => $mimeType
    ]);
}

if ($action !== 'translate' && $action !== 'analyze') {
    jsonResponse(['error' => 'Unknown action'], 400);
}

if (GEMINI_API_KEY === '') {
    jsonResponse(['error' => 'Gemini API key is not configured'], 500);
}

$data = json_decode(file_get_contents('php://input'), true);
$filename = basename((string) ($data['filename'] ?? ''));
$language = trim((string) ($data['language'] ?? ($_SESSION['user_language'] ?? 'English')));

if ($filename === '') {
    jsonResponse(['error' => 'Filename is required'], 400);
}

$filepath = UPLOAD_DIR . $filename;
if (!is_file($filepath)) {
    jsonResponse(['error' => 'File not found'], 404);
}

$fileData = base64_encode(file_get_contents($filepath));
$mimeType = mime_content_type($filepath) ?: 'application/octet-stream';

if ($action === 'translate') {
    $prompt = "Translate this document into {$language}. Return JSON with two keys: short_description and translation. short_description must be a short 1 sentence explanation of the document. translation must contain the translated text. Keep names, numbers, dates and formatting accurate.";
    $result = callGemini($mimeType, $fileData, $prompt, true);

    if (!$result['ok']) {
        jsonResponse(['error' => $result['error']], $result['http_code'] ?? 500);
    }

    $text = extractGeminiText($result['body']);
    $decoded = decodeJsonText($text);
    if (!is_array($decoded)) {
        jsonResponse(['error' => 'Failed to read translation response'], 500);
    }

    jsonResponse([
        'success' => true,
        'result' => [
            'short_description' => trim((string) ($decoded['short_description'] ?? '')),
            'translation' => trim((string) ($decoded['translation'] ?? ''))
        ]
    ]);
}

$prompt = "Analyze this document and return JSON with keys summary, document_type and key_points. Write ALL values in {$language}. summary must be 1-2 short sentences in {$language}. document_type must be a short label in {$language}. key_points must be an array with up to 5 important points in {$language}. Keep names, numbers, dates and factual details accurate.";
$result = callGemini($mimeType, $fileData, $prompt, true);

if (!$result['ok']) {
    jsonResponse(['error' => $result['error']], $result['http_code'] ?? 500);
}

$text = extractGeminiText($result['body']);
$decoded = decodeJsonText($text);
if (!is_array($decoded)) {
    jsonResponse(['error' => 'Failed to read analysis response'], 500);
}

jsonResponse([
    'success' => true,
    'result' => [
        'summary' => trim((string) ($decoded['summary'] ?? '')),
        'document_type' => trim((string) ($decoded['document_type'] ?? '')),
        'key_points' => array_values(array_filter(array_map('strval', $decoded['key_points'] ?? [])))
    ]
]);
