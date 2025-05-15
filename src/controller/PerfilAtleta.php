<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class PerfilAtleta extends BaseController
{
   private Database $database;
   private object $model;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("PerfilAtleta");
      $this->model = new $modelClass((object) $this->database);
   }
   public function obtenerPerfilUsuario(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerPerfilUsuario($datos);
   }
}
