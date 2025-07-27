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
 * Note: For production, consider loading these from the .env file as well.
 */
// define('ZOHO_ACCESS_TOKEN', 'zoho-access-token'); // TODO: Move to .env
// define('ZOHO_API_URL', 'https://www.zohoapis.com/crm/v2/Leads');

/**
 * Logging Configuration
 */
define('LOG_FILE', __DIR__ . '/logs/debug.log');
