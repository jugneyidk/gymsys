<?php

declare(strict_types=1);

use Gymsys\Controller\ApiController;
use Gymsys\Utils\ExceptionHandler;

require dirname(__DIR__) . '/vendor/autoload.php';
// header("Access-Control-Allow-Methods: GET, POST");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Access-Control-Allow-Origin: *");
try {
   session_start();
   require_once dirname(__DIR__) . '/config/config.php';
   $apiController = new ApiController();
   $apiController->handleRequest();
} catch (\TypeError $e) {
   $cleanMessage = ExceptionHandler::parseTypeErrorMessage($e->getMessage());
   $apiController->sendResponse(400, $cleanMessage, true);
   die;
} catch (\Throwable $e) {
   // $this->sendResponse(400, ['error' => $cleanMessage]);
   $errorMessage = json_decode($e->getMessage(), true);
   $apiController->sendResponse($errorMessage["code"] ?? 400, $errorMessage ?? $e->getMessage(), true);
   die;
}
exit;
