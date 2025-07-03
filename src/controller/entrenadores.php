<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
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
   }
   public function listadoEntrenadores(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoEntrenadores();
   }
   public function obtenerEntrenador(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerEntrenador($datos);
   }
   public function incluirEntrenador(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->incluirEntrenador($datos);
   }
   public function eliminarEntrenador(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->eliminarEntrenador($datos);
   }
   public function modificarEntrenador(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->modificarEntrenador($datos);
   }
   public function listadoGradosInstruccion(): array
   {
      $this->validarMetodoRequest("GET");

      return $this->model->listadoGradosInstruccion();
   }
}
