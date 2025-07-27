<?php

define('LOG_FILE_PATH', __DIR__ . '/logs/form.log'); // Use singular for file, and LOG_FILE_PATH for clarity

/**
 * Write a debug message to the log file with timestamp.
 *
 * @param string $message
 * @param string $level Optional log level (e.g., INFO, DEBUG, ERROR)
 */
function logDebug(string $message, string $level = 'INFO'): void {
    $formattedMessage = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), $level, $message);
    file_put_contents(LOG_FILE_PATH, $formattedMessage, FILE_APPEND);
}

/**
 * Print formatted debug output to screen (for dev/testing use).
 *
 * @param string $label
 * @param mixed $data
 */
function printDebug(string $label, $data): void {
    echo "<pre><strong>$label:</strong>\n";
    print_r($data);
    echo "</pre>\n";
}
