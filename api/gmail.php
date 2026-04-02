<?php
require_once '../config.php';

function gmailRequest($method, $url, $accessToken, $body = null, $headers = []) {
    $ch = curl_init($url);
    $defaultHeaders = [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json'
    ];

    if ($body !== null) {
        $defaultHeaders[] = 'Content-Type: application/json';
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($defaultHeaders, $headers));

    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
    }

    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['ok' => false, 'error' => $error ?: 'Request failed'];
    }

    $decoded = json_decode($response, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        return ['ok' => true, 'data' => $decoded];
    }

    return [
        'ok' => false,
        'error' => $decoded['error']['message'] ?? 'Google request failed',
        'http_code' => $httpCode,
        'data' => $decoded
    ];
}

function refreshGoogleAccessToken($refreshToken) {
    $postFields = http_build_query([
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'refresh_token' => $refreshToken,
        'grant_type' => 'refresh_token'
    ]);

    $ch = curl_init(GOOGLE_TOKEN_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['ok' => false, 'error' => $error ?: 'Token refresh failed'];
    }

    $decoded = json_decode($response, true);

    if ($httpCode >= 200 && $httpCode < 300 && !empty($decoded['access_token'])) {
        return ['ok' => true, 'data' => $decoded];
    }

    return ['ok' => false, 'error' => $decoded['error_description'] ?? $decoded['error'] ?? 'Token refresh failed'];
}

function getGmailAccountForCurrentUser() {
    requireAuth();
    $db = getDB();
    $stmt = $db->prepare('SELECT * FROM gmail_accounts WHERE user_id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    return $account ?: null;
}

function updateGmailTokens($id, $accessToken, $refreshToken, $expiresIn) {
    $db = getDB();
    $expiresAt = date('Y-m-d H:i:s', time() + max(60, (int) $expiresIn - 60));

    if ($refreshToken !== null && $refreshToken !== '') {
        $stmt = $db->prepare('UPDATE gmail_accounts SET access_token = ?, refresh_token = ?, expires_at = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$accessToken, $refreshToken, $expiresAt, $id]);
    } else {
        $stmt = $db->prepare('UPDATE gmail_accounts SET access_token = ?, expires_at = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$accessToken, $expiresAt, $id]);
    }
}

function getValidGmailAccessToken() {
    $account = getGmailAccountForCurrentUser();

    if (!$account) {
        return ['ok' => false, 'error' => 'Gmail account is not connected'];
    }

    $expiresAt = strtotime((string) ($account['expires_at'] ?? ''));
    if (!empty($account['access_token']) && $expiresAt && $expiresAt > time() + 30) {
        return ['ok' => true, 'token' => $account['access_token'], 'account' => $account];
    }

    if (empty($account['refresh_token'])) {
        return ['ok' => false, 'error' => 'Refresh token is missing. Please reconnect Gmail.'];
    }

    $refreshed = refreshGoogleAccessToken($account['refresh_token']);
    if (!$refreshed['ok']) {
        return ['ok' => false, 'error' => $refreshed['error']];
    }

    updateGmailTokens($account['id'], $refreshed['data']['access_token'], null, (int) ($refreshed['data']['expires_in'] ?? 3600));
    $account['access_token'] = $refreshed['data']['access_token'];

    return ['ok' => true, 'token' => $account['access_token'], 'account' => $account];
}

function getHeaderValue($headers, $name) {
    if (!is_array($headers)) {
        return '';
    }

    foreach ($headers as $header) {
        if (strcasecmp((string) ($header['name'] ?? ''), $name) === 0) {
            return (string) ($header['value'] ?? '');
        }
    }

    return '';
}

function decodeBase64Url($data) {
    $data = str_replace(['-', '_'], ['+', '/'], (string) $data);
    $padding = strlen($data) % 4;
    if ($padding > 0) {
        $data .= str_repeat('=', 4 - $padding);
    }
    return base64_decode($data) ?: '';
}

function extractBodyTextFromPayload($payload) {
    if (isset($payload['mimeType']) && strpos((string) $payload['mimeType'], 'text/plain') === 0 && !empty($payload['body']['data'])) {
        return decodeBase64Url($payload['body']['data']);
    }

    if (isset($payload['mimeType']) && strpos((string) $payload['mimeType'], 'text/html') === 0 && !empty($payload['body']['data'])) {
        return trim(strip_tags(decodeBase64Url($payload['body']['data'])));
    }

    if (!empty($payload['parts']) && is_array($payload['parts'])) {
        $plain = '';
        $html = '';

        foreach ($payload['parts'] as $part) {
            $text = extractBodyTextFromPayload($part);
            if ($text === '') {
                continue;
            }
            if (($part['mimeType'] ?? '') === 'text/plain' && $plain === '') {
                $plain = $text;
            } elseif ($html === '') {
                $html = $text;
            }
        }

        return $plain !== '' ? $plain : $html;
    }

    return '';
}

function extractAttachmentsFromPayload($payload, &$attachments = []) {
    if (!empty($payload['filename']) && !empty($payload['body']['attachmentId'])) {
        $attachments[] = [
            'filename' => $payload['filename'],
            'mime_type' => $payload['mimeType'] ?? 'application/octet-stream',
            'attachment_id' => $payload['body']['attachmentId'],
            'size' => (int) ($payload['body']['size'] ?? 0)
        ];
    }

    if (!empty($payload['parts']) && is_array($payload['parts'])) {
        foreach ($payload['parts'] as $part) {
            extractAttachmentsFromPayload($part, $attachments);
        }
    }

    return $attachments;
}

function formatEmailDate($rawDate) {
    if (!$rawDate) {
        return '';
    }
    $time = strtotime($rawDate);
    if (!$time) {
        return $rawDate;
    }
    return date('M j, g:i A', $time);
}
