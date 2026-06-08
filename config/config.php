<?php
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'ENVIRONMENT', 'JWT_SECRET', 'AES_KEY', 'SECURE_DB']);
date_default_timezone_set('America/Caracas');
define('ENVIRONMENT', in_array($_ENV['ENVIRONMENT'], ['DEVELOPMENT', 'PRODUCTION'], true) ? $_ENV['ENVIRONMENT'] : null);
if (ENVIRONMENT === 'DEVELOPMENT') {
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
} else {
   ini_set('display_errors', 0);
}
