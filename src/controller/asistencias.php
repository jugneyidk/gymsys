<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Asistencias extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Asistencias");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Asistencias", $this->database);
   }
   public function obtenerAsistencias(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerAsistencias($datos);
   }
   public function guardarAsistencias(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      return $this->model->guardarAsistencias($datos);
   }
   public function eliminarAsistencias(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarAsistencias($datos);
   }
}
