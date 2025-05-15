<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Model\Rolespermisos;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Entrenadores extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Entrenadores");
      $this->model = new $modelClass($this->database);
      $this->permisos = $this->obtenerPermisos("Entrenadores", $this->database);
      if ($this->permisos['leer'] == 0) ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }
   public function listadoEntrenadores(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoEntrenadores();
   }
   public function obtenerEntrenador(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerEntrenador($datos['id']);
   }
   public function incluirEntrenador(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->incluirEntrenador($datos);
   }
   public function eliminarEntrenador(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarEntrenador($datos);
   }
   public function modificarEntrenador(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->modificarEntrenador($datos);
   }
}
