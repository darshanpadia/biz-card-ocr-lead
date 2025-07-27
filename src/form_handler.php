<?php

require_once 'azure_client.php';
require_once 'config.php';
require_once 'utils.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['business_card'])) {
    $tmpPath = $_FILES['business_card']['tmp_name'];

    // Step 1: Send to Azure
    $operationUrl = sendToAzure($tmpPath, $apiKey, $modelUrl);

    // Step 2: Get Parsed Fields
    $fields = getAzureResult($operationUrl, $apiKey);
    if (empty($fields) || !is_array($fields)) {
        http_response_code(500);
        echo json_encode(['error' => 'Azure response empty or invalid']);
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

    header('Content-Type: application/json');
    echo json_encode($lead);
    exit;
}

echo json_encode(['error' => 'Invalid request']);
