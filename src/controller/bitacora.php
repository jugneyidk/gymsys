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
   }
   public function listadoBitacora(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoBitacora($datos);
   }
   public function obtenerAccion(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerAccion($datos);
   }
}
