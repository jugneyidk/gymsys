<?php
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'ENVIRONMENT', 'JWT_SECRET', 'AES_KEY', 'SECURE_DB']);
date_default_timezone_set('America/Caracas');
define('ENVIRONMENT', $_ENV['ENVIRONMENT'] == ('DEVELOPMENT' || 'PRODUCTION') ? $_ENV['ENVIRONMENT'] : null);
if (ENVIRONMENT == 'development') {
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
} else {
   ini_set('display_errors', 0);
}
