<?php
declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

if (class_exists(Dotenv::class)) {
    Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
}

if (empty($_ENV['AES_KEY'])) {
    $_ENV['AES_KEY'] = '0123456789abcdef0123456789abcdef';
    putenv('AES_KEY=' . $_ENV['AES_KEY']);
}

if (empty($_ENV['SECURE_DB'])) {
    $_ENV['SECURE_DB'] = 'gymsys_prueba';
    putenv('SECURE_DB=' . $_ENV['SECURE_DB']);
}

if (empty($_SERVER['HTTP_X_CLIENT_TYPE'])) {
    $_SERVER['HTTP_X_CLIENT_TYPE'] = 'web';
}

date_default_timezone_set('UTC');
if (!defined('Gymsys\Utils\ENVIRONMENT')) {
    define('Gymsys\Utils\ENVIRONMENT', 'testing'); // usado por JWTHelper para flags de cookies, etc.
}

$_ENV['JWT_SECRET']           = $_ENV['JWT_SECRET']           ?? 'test_secret_key_for_jwt_only_in_tests';
$_ENV['JWT_ISSUER']           = $_ENV['JWT_ISSUER']           ?? 'gymsys-tests';
$_ENV['JWT_ACCESS_TTL']       = $_ENV['JWT_ACCESS_TTL']       ?? '3600';     // 1 hora
$_ENV['JWT_REFRESH_TTL']      = $_ENV['JWT_REFRESH_TTL']      ?? '1209600';  // 14 días

// Opcional: si tu JWTHelper lee via getenv():
putenv('JWT_SECRET=' . $_ENV['JWT_SECRET']);
putenv('JWT_ISSUER=' . $_ENV['JWT_ISSUER']);
putenv('JWT_ACCESS_TTL=' . $_ENV['JWT_ACCESS_TTL']);
putenv('JWT_REFRESH_TTL=' . $_ENV['JWT_REFRESH_TTL']);