<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Model\Rolespermisos;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Entrenadores extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Entrenadores");
      $this->model = new $modelClass($this->database);
      $this->permisos = Rolespermisos::obtenerPermisosModulo("Entrenadores", $this->database);
      if (empty($this->permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
   }
   public function listadoEntrenadores()
   {
      $response = $this->model->listadoEntrenadores();
      return $response;
   }
   public function obtenerEntrenador(array $requestData)
   {
      $response = $this->model->obtenerEntrenador($requestData['id']);
      return $response;
   }
   public function incluirEntrenador(array $requestData)
   {
      $response = $this->model->incluirEntrenador($requestData);
      return $response;
   }
   public function eliminarEntrenador(array $requestData)
   {
      $response = $this->model->eliminarEntrenador($requestData);
      return $response;
   }
   public function modificarEntrenador(array $requestData)
   {
      $response = $this->model->modificarEntrenador($requestData);
      return $response;
   }
}
