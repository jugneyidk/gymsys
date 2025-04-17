<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Core\RedirectMiddleware;
class ApiController extends BaseController
{
  protected Database $database;
  protected array $routes;
  protected string $page;
  protected string $method;
  protected string|null $accion;
  protected array $requestData;
  public function __construct()
  {
    $this->database = new Database();
    $this->routes = require dirname(__DIR__) . "/core/routes.php";
    $this->page = $_GET['p'] ?? 'landing';
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->accion = $_GET['accion'] ?? null;
    $this->requestData = $this->getRequestData();
  }

  public function handleRequest()
  {
    RedirectMiddleware::handle($this->routes, $this->page, $this->method, (bool) $this->accion);
    $class = $this->getClass($this->page);
    $controller = new $class((object) $this->database);
    switch ($this->method) {
      case 'POST':
        $this->handlePost($controller);
        break;
      case 'GET':
        $this->handleGet($controller);
      default:
        throw new \BadMethodCallException("Metodo no permitido", 500);
    }
  }
  protected function getRequestData(): array
  {
    return $this->method === 'GET' ? $_GET : $_POST;
  }
  protected function handleGet($controller)
  {
    // Caso 1: GET para API (ej: ?p=user&accion=getData)
    if ($this->accion) {
      if (!method_exists($controller, $this->accion)) {
        throw new \Exception("Error en la solicitud", 400);
      }
      $data = $controller->{$this->accion}($this->requestData);
      return $this->sendResponse(200, $data);
    }
    // Caso 2: GET para vista (ej: ?p=user)
    else {
      $controller->renderVista(ucwords($this->page));
      return true;
    }
  }
  protected function handlePost($controller)
  {
    // Validar acción en POST (ej: ?p=user&accion=update)
    if (empty($this->accion) || !method_exists($controller, $this->accion)) {
      throw new \Exception("Error en la solicitud", 400);
    }
    $response = $controller->{$this->accion}($this->requestData);
    return $this->sendResponse(200, $response);
  }
}
