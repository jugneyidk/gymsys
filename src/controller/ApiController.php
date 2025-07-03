<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Core\RedirectMiddleware;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\JWTHelper;

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
      $this->routes = require dirname(__DIR__) . "/core/routes.php";
      $this->page = $_GET['p'] ?? 'landing';
      $this->method = $_SERVER['REQUEST_METHOD'];
      $this->accion = $_GET['accion'] ?? null;
      $this->requestData = $this->getRequestData();
   }

   public function handleRequest()
   {
      if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
         $headers = getallheaders();
         if (isset($headers['Authorization'])) {
            $_SERVER['HTTP_AUTHORIZATION'] = $headers['Authorization'];
         }
      }
      RedirectMiddleware::handle($this->routes, $this->page, $this->method, (bool) $this->accion);
      $this->database = new Database();
      $class = $this->getClass($this->page);
      $controller = new $class((object) $this->database);
      switch ($this->method) {
         case 'POST':
            $this->handlePost($controller);
            break;
         case 'GET':
            $this->handleGet($controller);
         default:
            ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
   }
   protected function getRequestData(): array
   {
      return $this->method === 'GET' ? $_GET : $_POST;
   }
   protected function handleGet(object $controller)
   {
      // Caso 1: GET para API (ej: ?p=user&accion=getData)
      if ($this->accion) {
         if (!method_exists($controller, $this->accion)) {
            ExceptionHandler::throwException("Error en la solicitud: no se encontró el metodo", 400, \BadMethodCallException::class);
         }
         $data = $controller->{$this->accion}($this->requestData);
         return $this->sendResponse(200, $data);
      }
      // Caso 2: GET para vista (ej: ?p=user)
      else {
         $permisosNav = Rolespermisos::obtenerPermisosNav($this->database) ?: [];
         $permisosModulo = Rolespermisos::obtenerPermisosModulo($this->page, $this->database);
         $controller->renderVista(ucwords($this->page), $permisosNav, $permisosModulo);
         return true;
      }
   }
   protected function handlePost(object $controller)
   {
      // Validar acción en POST (ej: ?p=user&accion=modificar)
      if (empty($this->accion) || !method_exists($controller, $this->accion)) {
         ExceptionHandler::throwException("Error en la solicitud: el método llamado no existe", 400, \BadMethodCallException::class);
      }
      $response = $controller->{$this->accion}($this->requestData);
      return $this->sendResponse(200, $response);
   }
}
