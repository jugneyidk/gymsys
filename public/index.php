<?php

declare(strict_types=1);

use Gymsys\Controller\ApiController;
use Gymsys\Utils\ExceptionHandler;

require dirname(__DIR__) . '/vendor/autoload.php';

// Iniciar sesión solo si ya existe una 
if (session_status() === PHP_SESSION_NONE && isset($_COOKIE['PHPSESSID'])) {
    session_start();
}

// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Client-Type");
header("Access-Control-Max-Age: 86400");

// Preflight de CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    require_once dirname(__DIR__) . '/config/config.php';
    $apiController = new ApiController();
    $apiController->handleRequest();
    exit;

} catch (\TypeError $e) {
    if ($_ENV['ENVIRONMENT'] === 'PRODUCTION') {
        $cleanMessage = ExceptionHandler::parseTypeErrorMessage($e->getMessage());
        $apiController->sendResponse(400, $cleanMessage, true);
        die;
    }
    $apiController->sendResponse(400, "{$e->getMessage()}: {$e->getTraceAsString()}", true);
    die;

} catch (\Throwable $e) {
    if (!isset($apiController)) {
        echo $e->getMessage();
        die;
    }
    $errorMessage = json_decode($e->getMessage(), true);
    $apiController->sendResponse($errorMessage["code"] ?? 400, $errorMessage ?? $e->getMessage(), true);
    die;
}
