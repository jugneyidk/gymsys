<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Logout extends BaseController
{
   private Database $database;
   private object $model;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Logout");
      $this->model = new $modelClass($this->database);
   }
   public function logOut(): never
   {
      $response = $this->model->logOut();
      if ($response) {
         header("Location: . ");
      }
      exit;
   }
}
