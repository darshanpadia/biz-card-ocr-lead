<?php

/**
 * Write a debug message to WordPress debug log if WP_DEBUG is enabled.
 *
 * @param string $message
 * @param string $level Optional log level (e.g., INFO, DEBUG, ERROR)
 */
function logDebug(string $message, string $level = 'INFO'): void {
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        error_log("[$level] $message");  // goes to wp-content/debug.log
    }
}


// /**
//  * Print formatted debug output to screen (for dev/testing use).
//  *
//  * @param string $label
//  * @param mixed $data
//  */
// function printDebug(string $label, $data): void {
//     echo "<pre><strong>$label:</strong>\n";
//     print_r($data);
//     echo "</pre>\n";
// }
