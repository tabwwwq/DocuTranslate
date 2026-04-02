<?php
require_once 'gmail.php';
require_once 'analyze_shared.php';
requireAuth();

if (GEMINI_API_KEY === '') {
    jsonResponse(['error' => 'Gemini API key is not configured'], 500);
}

$messageText = trim((string) ($_POST['text'] ?? ''));
$language = trim((string) ($_POST['language'] ?? ($_SESSION['user_language'] ?? 'English')));
$mode = trim((string) ($_POST['mode'] ?? 'analyze'));

if ($messageText === '') {
    jsonResponse(['error' => 'Email text is required'], 400);
}

if ($mode === 'translate') {
    $prompt = "Translate this email into {$language}. Return JSON with keys short_description and translation. Keep names, emails, numbers, and dates accurate. Email:\n\n" . $messageText;
    $response = callGeminiTextOnly($prompt, true);
    if (!$response['ok']) {
        jsonResponse(['error' => $response['error']], 500);
    }
    $text = extractGeminiText($response['body']);
    $decoded = decodeJsonText($text);
    if (!is_array($decoded)) {
        jsonResponse(['error' => 'Invalid translation response'], 500);
    }
    jsonResponse(['success' => true, 'result' => [
        'short_description' => trim((string) ($decoded['short_description'] ?? '')),
        'translation' => trim((string) ($decoded['translation'] ?? ''))
    ]]);
}

$prompt = "Analyze this email and return JSON with keys summary and key_points. summary must be 1-2 sentences. key_points must be an array of up to 5 short strings. Email:\n\n" . $messageText;
$response = callGeminiTextOnly($prompt, true);
if (!$response['ok']) {
    jsonResponse(['error' => $response['error']], 500);
}
$text = extractGeminiText($response['body']);
$decoded = decodeJsonText($text);
if (!is_array($decoded)) {
    jsonResponse(['error' => 'Invalid analysis response'], 500);
}
jsonResponse(['success' => true, 'result' => [
    'summary' => trim((string) ($decoded['summary'] ?? '')),
    'key_points' => array_values(array_filter(array_map('strval', $decoded['key_points'] ?? [])))
]]);
