<?php
/*
 * Database configuration
 */

define('DB_HOST', '192.168.0.201:3306');

/*
 * Local Database configuration
 */

define('DB_USERNAME', 'accounts');
define('DB_PASSWORD', 'accounts');
define('DB_NAME', 'accounts');

/*
 * Remote Database configuration
 */

// define('DB_USERNAME', 'zaweb_accounts');

// define('DB_PASSWORD', '?#x-45p7nh{0)_k%.b');
// define('DB_NAME', 'zaweb_accounts');

define('DEVELOPMENT_ENVIRONMENT', true);
define('maintenance_mode', false);
define('login_time', 1800);
define('CORE_ASSETS_DIR', ROOT . DS . 'assets');
define('APP_DIR', ROOT . DS . 'application');
define('LOGIN_KEY', '85e28e9a33ae09caaacad9dd7350e64e');
date_default_timezone_set('Africa/Johannesburg');
/*
 * Email settings
 */
define("emailCompany", "Company");
define("emailAddress", "");
define("emailServer", "mail.zawebs.com");
define("emailPassword", "");
ini_set('log_errors', 'On');
ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
define('DOMAIN', $_SERVER['HTTP_HOST']);

if (DEVELOPMENT_ENVIRONMENT == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
}

if (is_file(ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log')) {
    $size = filesize(ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');

    if ($size >= 62500000) {
        unlink(ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
    }
}
