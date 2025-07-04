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
       if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
      unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
      $response = $this->model->logOut();
      if ($response) {
         header("Location: . ");
      }
      exit;
   }
}
