<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Recovery extends BaseController
{
   private Database $database;
   private object $model;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Recovery");
      $this->model = new $modelClass($this->database);
   }

   public function generarRecuperacion(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->generarRecuperacion($datos);
   }
}
