<?php
/*
Plugin Name: Business Card OCR Lead
Description: Upload a business card image and auto-fill lead data using Azure OCR. Additionally integrate with Zoho CRM.
Version: 1.0
Author: Darshan Padia
*/
defined('ABSPATH') || exit;

// autoload dependencies
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

// Load dependencies    
require_once plugin_dir_path(__FILE__) . 'src/config.php';
require_once plugin_dir_path(__FILE__) . 'src/azure_client.php';
require_once plugin_dir_path(__FILE__) . 'src/utils.php';
// require_once plugin_dir_path(__FILE__) . 'src/zoho_client.php'; // add later

add_action('wp_ajax_bcol_handle_card_upload', 'bcol_handle_card_upload');
add_action('wp_ajax_nopriv_bcol_handle_card_upload', 'bcol_handle_card_upload');

function bcol_handle_card_upload() {
    require_once plugin_dir_path(__FILE__) . 'src/form_handler.php';
    wp_die(); // required to terminate and return a proper response
}

// Shortcode to render form
function bcol_render_upload_form() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/form.php';
    return ob_get_clean();
}
add_shortcode('bcol_biz_card_form', 'bcol_render_upload_form');

// Enqueue CSS/JS if needed (not mandatory yet)
function bcol_enqueue_assets() {
    // wp_enqueue_style('bcol-style', plugins_url('assets/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'bcol_enqueue_assets');

