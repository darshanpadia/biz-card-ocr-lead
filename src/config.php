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

/**
 * Zoho CRM Configuration
 */
    $zohoClientId     = $_ENV['ZOHO_CLIENT_ID'];
    $zohoClientSecret = $_ENV['ZOHO_CLIENT_SECRET'];
    $zohoRedirectUri  = $_ENV['ZOHO_REDIRECT_URI'];
    $zohoRefreshToken = $_ENV['ZOHO_REFRESH_TOKEN'];
    $zohoBaseUrl      = $_ENV['ZOHO_BASE_URL'];

// /**
//  * Logging Configuration
//  */
// define('LOG_FILE', __DIR__ . '/logs/debug.log');
