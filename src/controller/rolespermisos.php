<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos as ModelRolespermisos;
use Gymsys\Utils\ExceptionHandler;

class Rolespermisos extends BaseController
{
   protected Database $database;
   private object $model;
   private array $permisos;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Rolespermisos");
      $this->model = new $modelClass($this->database);
      $this->permisos = $this->obtenerPermisos("Rolespermisos", $this->database);
      if ($this->permisos['leer'] == 0) ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }

   public function obtenerRol(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerRol($datos);
   }
   public function obtenerRolUsuario(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerRolUsuario($datos);
   }
   public function listadoRoles(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoRoles($datos);
   }
   public function incluirRol(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->incluirRol($datos);
   }
   public function modificarRol(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->modificarRol($datos);
   }
   public function asignarRol(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->asignarRol($datos);
   }
   public function eliminarRol(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarRol($datos);
   }
}
