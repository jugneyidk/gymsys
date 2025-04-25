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
      $this->permisos = Rolespermisos::obtenerPermisosModulo("Asistencias", $this->database);
      if (empty($this->permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
   }
   public function obtenerAsistencias(array $datos)
   {
      return $this->model->obtenerAsistencias($datos);
   }
   public function guardarAsistencias(array $datos)
   {
      return $this->model->guardarAsistencias($datos);
   }
   public function eliminarAsistencias(array $datos)
   {
      return $this->model->eliminarAsistencias($datos);
   }
}
