<?php
namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Dashboard extends BaseController
{
   private Database $database;
   protected $model;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("dashboard");
      $this->model = new $modelClass($this->database);
   }
   public function obtenerDatosSistema(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerDatosSistema();
   }
}