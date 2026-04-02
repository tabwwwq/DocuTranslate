<?php
require_once '../config.php';

function extractGeminiText($result) {
    if (!isset($result['candidates']) || !is_array($result['candidates'])) {
        return '';
    }

    foreach ($result['candidates'] as $candidate) {
        if (!empty($candidate['content']['parts']) && is_array($candidate['content']['parts'])) {
            $text = '';
            foreach ($candidate['content']['parts'] as $part) {
                if (isset($part['text'])) {
                    $text .= $part['text'];
                }
            }
            if (trim($text) !== '') {
                return $text;
            }
        }
    }

    return '';
}

function callGeminiRequest($requestBody) {
    $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/' . rawurlencode(GEMINI_MODEL) . ':generateContent';
    $attempt = 0;
    $maxAttempts = 3;
    $lastResponse = null;
    $lastHttpCode = 0;
    $lastCurlError = '';

    while ($attempt < $maxAttempts) {
        $attempt++;

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-goog-api-key: ' . GEMINI_API_KEY
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $lastResponse = $response;
        $lastHttpCode = $httpCode;
        $lastCurlError = $curlError;

        if ($response !== false && $httpCode === 200) {
            return [
                'ok' => true,
                'body' => json_decode($response, true)
            ];
        }

        if (!in_array($httpCode, [429, 500, 502, 503, 504], true)) {
            break;
        }

        sleep($attempt);
    }

    if ($lastResponse === false) {
        return [
            'ok' => false,
            'error' => 'Failed to connect to the Gemini API: ' . $lastCurlError
        ];
    }

    $decoded = json_decode((string) $lastResponse, true);
    $errorText = $decoded['error']['message'] ?? 'Gemini API error';

    return [
        'ok' => false,
        'error' => $errorText,
        'http_code' => $lastHttpCode
    ];
}

function callGemini($mimeType, $fileData, $prompt, $jsonMode = false) {
    $requestBody = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt],
                    ['inline_data' => ['mime_type' => $mimeType, 'data' => $fileData]]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.2,
            'maxOutputTokens' => 8192
        ]
    ];

    if ($jsonMode) {
        $requestBody['generationConfig']['responseMimeType'] = 'application/json';
    }

    return callGeminiRequest($requestBody);
}

function callGeminiTextOnly($prompt, $jsonMode = false) {
    $requestBody = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.2,
            'maxOutputTokens' => 4096
        ]
    ];

    if ($jsonMode) {
        $requestBody['generationConfig']['responseMimeType'] = 'application/json';
    }

    return callGeminiRequest($requestBody);
}

function decodeJsonText($text) {
    $decoded = json_decode($text, true);

    if (is_array($decoded)) {
        return $decoded;
    }

    $start = strpos($text, '{');
    $end = strrpos($text, '}');

    if ($start !== false && $end !== false && $end > $start) {
        $decoded = json_decode(substr($text, $start, $end - $start + 1), true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }

    return null;
}
