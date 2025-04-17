<?php
declare(strict_types=1);
use Gymsys\Controller\ApiController;
require dirname(__DIR__) . '/vendor/autoload.php';
// header("Access-Control-Allow-Methods: GET, POST");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Access-Control-Allow-Origin: *");

// if (!empty($_GET['p'])) {
//   $p = $_GET['p'];
//   if (!isset($_SESSION['id_usuario']) && ($p != "login") && ($p != "perfil_atleta") && ($p != "carnet") && ($p != "recovery") && ($p != "reestablecer") && ($p != "manual")) {
//     session_unset();
//     session_destroy();
//     header("Location: ?p=login");
//   }
// } else {
//   if (isset($_SESSION['id_usuario'])) {
//     $p = "dashboard";
//   } else {
//     $p = "landing";
//   }
// }
try {
  session_start();
  require_once dirname(__DIR__) . '/config/config.php';
  $apiController = new ApiController();
  $apiController->handleRequest();
} catch (\Throwable $e) {
  // $cleanMessage = Validate::parseTypeErrorMessage($e->getMessage());
  // $this->sendResponse(400, ['error' => $cleanMessage]);
  $errorMessage = json_encode(["ok" => false, "error" => $e->getMessage()]);
  if (ENVIRONMENT == "DEVELOPMENT") {
    $errorMessage = json_encode(["ok" => false, "error" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()]);
  }
  http_response_code($e->getCode() ?? 400);
  echo $errorMessage;
  die;
}
exit;
// require_once "comunes/carga.php";