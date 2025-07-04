<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Login extends BaseController
{
   protected Database $database;
   protected object $model;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("login");
      $this->model = new $modelClass($this->database);
   }
   public function authUsuario(array $requestData): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->authUsuario($requestData);
   }
}
