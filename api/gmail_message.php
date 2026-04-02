<?php
require_once 'gmail.php';
requireAuth();

$id = trim((string) ($_GET['id'] ?? ''));
if ($id === '') {
    jsonResponse(['error' => 'Message id is required'], 400);
}

$auth = getValidGmailAccessToken();
if (!$auth['ok']) {
    jsonResponse(['error' => $auth['error'], 'needs_connect' => true], 400);
}

$detail = gmailRequest('GET', GMAIL_API_BASE . '/messages/' . rawurlencode($id) . '?format=full', $auth['token']);
if (!$detail['ok']) {
    jsonResponse(['error' => $detail['error']], 400);
}

$headers = $detail['data']['payload']['headers'] ?? [];
$body = trim(extractBodyTextFromPayload($detail['data']['payload'] ?? []));
$attachments = [];
extractAttachmentsFromPayload($detail['data']['payload'] ?? [], $attachments);

jsonResponse([
    'success' => true,
    'message' => [
        'id' => $detail['data']['id'],
        'subject' => getHeaderValue($headers, 'Subject') ?: '(No subject)',
        'from' => getHeaderValue($headers, 'From') ?: 'Unknown sender',
        'date' => formatEmailDate(getHeaderValue($headers, 'Date')),
        'snippet' => $detail['data']['snippet'] ?? '',
        'body' => $body !== '' ? $body : ($detail['data']['snippet'] ?? ''),
        'attachments' => $attachments
    ]
]);
