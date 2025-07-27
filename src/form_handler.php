<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/**
 * Azure Form Recognizer Configuration
 */
$apiKey   = $_ENV['AZURE_DI_API_KEY']     ?? '';
$endpoint = $_ENV['AZURE_DI_ENDPOINT']    ?? '';
$modelUrl = $_ENV['AZURE_DI_MODEL_URL']   ?? '';

require_once 'azure_client.php';
require_once __DIR__ . '/config.php';
require_once 'utils.php';

header('Content-Type: application/json');

error_log("FORM HANDLER API Key: $apiKey");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['business_card'])) {
    $tmpPath = $_FILES['business_card']['tmp_name'];

    // Step 1: Send to Azure
    $operationUrl = sendToAzure($tmpPath, $apiKey, $modelUrl);

    // Step 2: Get Parsed Fields
    $fields = getAzureResult($operationUrl, $apiKey);
    if (empty($fields) || !is_array($fields)) {
        http_response_code(500);
        wp_send_json_error(['message' => 'Azure response empty or invalid']);
        exit;
    }

    // Step 3: Build Lead Object
    $lead = [
        'fullName' => trim(
            ($fields['ContactNames']['valueArray'][0]['valueObject']['FirstName']['content'] ?? '') . ' ' .
            ($fields['ContactNames']['valueArray'][0]['valueObject']['LastName']['content'] ?? '')
        ),
        'email' => $fields['Emails']['valueArray'][0]['content'] ?? '',
        'phone' => $fields['MobilePhones']['value'][0]['valuePhoneNumber']
            ?? $fields['WorkPhones']['valueArray'][0]['valuePhoneNumber']
            ?? '',
        'company' => $fields['CompanyNames']['valueArray'][0]['content'] ?? '',
        'address' => $fields['Addresses']['valueArray'][0]['content'] ?? '',
    ];

    wp_send_json($lead);
    exit;
}

wp_send_json_error(['message' => 'Invalid request']);