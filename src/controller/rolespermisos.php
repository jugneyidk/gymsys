<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
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
      if ($this->permisos['leer'] == 0)
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }
   public function obtenerPermisosNav(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model::obtenerPermisosNav($this->database);
   }
   public function obtenerRol(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerRol($datos);
   }
   public function obtenerRolUsuario(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerRolUsuario($datos);
   }
   public function listadoRoles(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->listadoRoles($datos);
   }
   public function incluirRol(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->incluirRol($datos);
   }
   public function csrfnuevo(): array
   {
      $this->validarMetodoRequest("GET");
      return ['nuevo_csrf_token' => $this->generateCsrfToken()];
   }
   public function modificarRol(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->modificarRol($datos);
   }
   public function asignarRol(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->asignarRol($datos);
   }
   public function eliminarRol(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->eliminarRol($datos);
   }
}
