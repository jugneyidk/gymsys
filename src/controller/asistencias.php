<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
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
      if ($this->permisos['leer'] == 0) ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }
   public function obtenerAsistencias(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerAsistencias($datos);
   }
   public function guardarAsistencias(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->guardarAsistencias($datos);
   }
   public function eliminarAsistencias(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarAsistencias($datos);
   }
}
