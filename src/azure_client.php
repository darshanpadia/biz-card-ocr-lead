<?php

require_once 'config.php';
require_once 'utils.php';

/**
 * Sends an image to Azure Document Intelligence API (Business Card model).
 *
 * @param string $imagePath  Path to the image file
 * @param string $apiKey     Azure API Key
 * @param string $modelUrl   Azure Prebuilt Business Card model URL
 * @return string            Operation URL for result polling
 */
function sendToAzure(string $imagePath, string $apiKey, string $modelUrl): string
{
    $fileData = file_get_contents($imagePath);

    $headers = [
        'Content-Type: application/octet-stream',
        'Ocp-Apim-Subscription-Key: ' . $apiKey
    ];

    $ch = curl_init($modelUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $fileData,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_HEADER => true,
    ]);

    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    // Split response into headers and body
    $headersPart = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    // Extract Operation-Location header
    preg_match('/operation-location:\s*(.*)/i', $headersPart, $matches);
    $operationUrl = trim($matches[1] ?? '');

    logDebug("Azure Operation URL: $operationUrl");

    return $operationUrl;
}

/**
 * Polls Azure API for the result of the document analysis.
 *
 * @param string $operationUrl   The URL to check for analysis result
 * @param string $apiKey         Azure API Key
 * @return array                 Extracted fields from the analyzed document
 */
function getAzureResult(string $operationUrl, string $apiKey): array
{
    $attempts = 0;
    $maxAttempts = 10;
    $resultData = [];

    do {
        sleep(2); // wait before retry
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Ocp-Apim-Subscription-Key: ' . $apiKey
            ]
        ]);

        $result = file_get_contents($operationUrl, false, $context);
        $resultData = json_decode($result, true);
        $status = $resultData['status'] ?? '';
        $attempts++;
    } while ($status !== 'succeeded' && $attempts < $maxAttempts);

    logDebug("Azure Final Response: " . json_encode($resultData));

    return $resultData['analyzeResult']['documents'][0]['fields'] ?? [];
}
