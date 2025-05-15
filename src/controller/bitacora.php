<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Bitacora extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Bitacora");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Bitacora", $this->database);
      if ($this->permisos['leer'] == 0) ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }
   public function listadoBitacora(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoBitacora($datos);
   }
   public function obtenerAccion(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerAccion($datos);
   }
}
