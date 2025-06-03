<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Restablecer extends BaseController
{
   private Database $database;
   private object $model;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Restablecer");
      $this->model = new $modelClass($this->database);
   }
   public function verificarToken(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->verificarToken($datos);
   }

   public function restablecerPassword(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->restablecerPassword($datos);
   }
}
