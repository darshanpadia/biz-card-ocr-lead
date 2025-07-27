<?php

require_once __DIR__ . '/zoho_client.php';

$testLead = [
    "Last_Name" => "Test",
    "First_Name" => "Darshan",
    "Email" => "darshan@example.com",
    "Phone" => "9876543210",
    "Company" => "Test Company"
];

$response = createZohoLead($testLead);

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
