<?php
require_once 'gmail.php';
requireAuth();

$auth = getValidGmailAccessToken();
if (!$auth['ok']) {
    jsonResponse(['error' => $auth['error'], 'needs_connect' => true], 400);
}

$maxResults = max(1, min(20, (int) ($_GET['maxResults'] ?? 15)));
$search = trim((string) ($_GET['q'] ?? ''));

$query = ['labelIds=INBOX', 'maxResults=' . $maxResults];
if ($search !== '') {
    $query[] = 'q=' . rawurlencode($search . ' in:inbox');
}

$list = gmailRequest('GET', GMAIL_API_BASE . '/messages?' . implode('&', $query), $auth['token']);
if (!$list['ok']) {
    jsonResponse(['error' => $list['error']], 400);
}

$messages = [];
foreach (($list['data']['messages'] ?? []) as $item) {
    $detail = gmailRequest('GET', GMAIL_API_BASE . '/messages/' . rawurlencode($item['id']) . '?format=metadata&metadataHeaders=Subject&metadataHeaders=From&metadataHeaders=Date', $auth['token']);
    if (!$detail['ok']) {
        continue;
    }

    $headers = $detail['data']['payload']['headers'] ?? [];
    $messages[] = [
        'id' => $detail['data']['id'],
        'thread_id' => $detail['data']['threadId'] ?? '',
        'subject' => getHeaderValue($headers, 'Subject') ?: '(No subject)',
        'from' => getHeaderValue($headers, 'From') ?: 'Unknown sender',
        'date' => formatEmailDate(getHeaderValue($headers, 'Date')),
        'snippet' => $detail['data']['snippet'] ?? '',
        'has_attachments' => !empty($detail['data']['payload']['parts'])
    ];
}

jsonResponse([
    'success' => true,
    'connected_email' => $auth['account']['google_email'] ?? '',
    'messages' => $messages,
    'count' => count($messages)
]);
