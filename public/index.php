<?php

declare(strict_types=1);

use Gymsys\Controller\ApiController;
use Gymsys\Utils\ExceptionHandler;

require dirname(__DIR__) . '/vendor/autoload.php';
// header("Access-Control-Allow-Methods: GET, POST");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Access-Control-Allow-Origin: *");
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
