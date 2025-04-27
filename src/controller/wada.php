<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;

class Wada extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Wada");
      $this->model = new $modelClass((object) $database);
      $this->permisos = Rolespermisos::obtenerPermisosModulo("Wada", $this->database);
      if (empty($this->permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
   }
   public function incluirWada(array $datos): array
   {
      if ($_SERVER['REQUEST_METHOD'] !== "POST") {
         ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
      return $this->model->incluirWada($datos);
   }
   public function listadoPorVencer(): array
   {
      if ($_SERVER['REQUEST_METHOD'] !== "GET") {
         ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
      return $this->model->listadoPorVencer();
   }
   public function listadoWada(): array
   {
      if ($_SERVER['REQUEST_METHOD'] !== "GET") {
         ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
      return $this->model->listadoWada();
   }
   public function obtenerWada(array $datos): array
   {
      if ($_SERVER['REQUEST_METHOD'] !== "GET") {
         ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
      return $this->model->obtenerWada($datos);
   }
   public function modificarWada(array $datos): array
   {
      if ($_SERVER['REQUEST_METHOD'] !== "POST") {
         ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
      return $this->model->modificarWada($datos);
   }
   public function eliminarWada(array $datos): array
   {
      if ($_SERVER['REQUEST_METHOD'] !== "POST") {
         ExceptionHandler::throwException("Método no permitido", 405, \BadMethodCallException::class);
      }
      return $this->model->eliminarWada($datos);
   }
}
