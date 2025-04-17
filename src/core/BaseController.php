<?php

namespace Gymsys\Core;

class BaseController
{
  public static function renderVista(string $vista): never
  {
    header('Content-Type: text/html');
    require_once dirname(__DIR__) . "/../src/view/{$vista}.php";
    exit;
  }
  public function sendResponse(int $statusCode, mixed $data): never
  {
    http_response_code($statusCode);
    if (!empty($data)) {
      header('Content-Type: application/json');
      echo json_encode(["ok" => true, "data" => $data]);
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
      throw new \BadFunctionCallException("No existe la clase del controlador", 500);
    }
    return $class;
  }
  public function getModel(string $page): string
  {
    $class = "Gymsys\Model\\" . ucwords($page);
    if (!class_exists($class)) {
      throw new \BadFunctionCallException("No existe la clase del modelo", 500);
    }
    return $class;
  }
}
