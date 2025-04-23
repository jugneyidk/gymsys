<?php

namespace Gymsys\Core;

use Gymsys\Utils\ExceptionHandler;

class BaseController
{
   public static function renderVista(string $vista, array $permisosNav, array $permisosModulo): never
   {
      header('Content-Type: text/html');
      extract($permisosNav);
      extract($permisosModulo);
      require dirname(__DIR__) . "/../src/view/{$vista}.php";
      require dirname(__DIR__) . "/../src/view/comunes/carga.php";
      exit;
   }
   public function sendResponse(int $statusCode, mixed $data, bool $exception = false): never
   {
      http_response_code($statusCode);
      if (!empty($data)) {
         header('Content-Type: application/json');
         $ok = $exception ? false : true;
         echo json_encode(["ok" => $ok, "data" => $data]);
      }
      exit;
   }
   public function getPostData(): array
   {
      $data = json_decode(file_get_contents('php://input'), true);
      return $data;
   }
   public function getClass(string $page): string
   {
      $class = "Gymsys\Controller\\" . ucwords($page);
      if (!class_exists($class)) {
         ExceptionHandler::throwException("No existe el controlador con este nombre", 400, \BadFunctionCallException::class);
      }
      return $class;
   }
   public function getModel(string $page): string
   {
      $class = "Gymsys\Model\\" . ucwords($page);
      if (!class_exists($class)) {
         ExceptionHandler::throwException("No existe el modelo con este nombre", 400, \BadFunctionCallException::class);
      }
      return $class;
   }
}
