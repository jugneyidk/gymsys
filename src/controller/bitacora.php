<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Bitacora extends BaseController
{
   private Database $database;
   private object $model;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Bitacora");
      $this->model = new $modelClass((object) $this->database);
   }
   public function listadoBitacora(array $datos): array
   {
      return $this->model->listadoBitacora($datos);
   }
   public function obtenerAccion(array $datos): array
   {
      return $this->model->obtenerAccion($datos);
   }
}
