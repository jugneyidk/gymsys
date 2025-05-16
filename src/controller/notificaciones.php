<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Notificaciones extends BaseController
{
   private Database $database;
   private object $model;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Notificaciones");
      $this->model = new $modelClass($this->database);
   }
   public function obtenerNotificaciones(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerNotificaciones();
   }
   public function marcarLeida(array $requestData): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->marcarLeida($requestData);
   }
   public function marcarTodoLeido(): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->marcarTodoLeido();
   }
   public function verTodas(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->verTodas($datos);
   }
}
