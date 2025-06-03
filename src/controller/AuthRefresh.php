<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class AuthRefresh extends BaseController
{
   private Database $database;
   private object $model;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("AuthRefresh");
      $this->model = new $modelClass((object) $this->database);
   }
   public function refreshToken(): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->refreshToken();
   }
}
