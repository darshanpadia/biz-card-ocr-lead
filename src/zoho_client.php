<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

function getZohoAccessToken() {
    $clientId     = $_ENV['ZOHO_CLIENT_ID'];
    $clientSecret = $_ENV['ZOHO_CLIENT_SECRET'];
    $refreshToken = $_ENV['ZOHO_REFRESH_TOKEN'];
    $apiDomain    = "https://accounts.zoho.in"; // e.g., https://accounts.zoho.in

    $client = new Client();

    try {
        $response = $client->post("$apiDomain/oauth/v2/token", [
            'form_params' => [
                'refresh_token' => $refreshToken,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'grant_type'    => 'refresh_token'
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'] ?? null;

    } catch (RequestException $e) {
        error_log("Zoho Auth Error: " . $e->getMessage());
        return null;
    }
}

function createZohoLead($leadData) {
    $accessToken = getZohoAccessToken();
    if (!$accessToken) {
        return ['error' => 'Unable to fetch Zoho access token'];
    }

    $client = new Client();
    $apiUrl = $_ENV['ZOHO_BASE_URL'] . '/crm/v2/Leads';

    try {
        $response = $client->post($apiUrl, [
            'headers' => [
                'Authorization' => "Zoho-oauthtoken $accessToken",
                'Content-Type'  => 'application/json'
            ],
            'json' => [
                'data' => [$leadData]
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body;
    } catch (RequestException $e) {
        $errorBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        error_log("Zoho Lead Error: " . $errorBody);
        return ['error' => 'Failed to create lead in Zoho'];
    }
}
