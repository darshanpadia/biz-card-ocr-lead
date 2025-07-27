<?php

require_once 'azure_client.php';
require_once 'config.php';
require_once 'utils.php';
// require_once 'zoho_client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['business_card'])) {
    $tmpPath = $_FILES['business_card']['tmp_name'];

    // Step 1: Send to Azure
    $operationUrl = sendToAzure($tmpPath, $apiKey, $modelUrl);

    // Step 2: Get Parsed Fields
    $fields = getAzureResult($operationUrl, $apiKey);

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

    // Debug or Log Output
    printDebug("Parsed Lead", $lead);

    // Step 4: Send to Zoho (if enabled)
    // $zohoResponse = sendToZoho($lead);
    // printDebug("Zoho Response", $zohoResponse);
} else {
    echo "Upload a valid image file.";
}
