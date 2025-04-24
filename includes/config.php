<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable for debugging, disable in production
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/logs/error.log');

// Session management
session_start([
    'cookie_lifetime' => 86400, // 1 day
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

// Base URL configuration
define('BASE_URL', 'http://localhost/feedback-system/');
define('ASSETS_URL', BASE_URL.'assets/');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'feedback_system');

// Security
define('CSRF_TOKEN_LIFE', 3600); // 1 hour
define('PASSWORD_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_OPTIONS', ['cost' => 12]);

// Ensure BASE_URL ends with slash
if (substr(BASE_URL, -1) !== '/') {
    define('BASE_URL', BASE_URL.'/');
}
?>