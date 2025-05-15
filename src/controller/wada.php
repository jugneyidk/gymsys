<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;

class Wada extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Wada");
      $this->model = new $modelClass((object) $database);
      $this->permisos = $this->obtenerPermisos("Wada", $this->database);
      if ($this->permisos['leer'] == 0) ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }
   public function listadoPorVencer(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoPorVencer();
   }
   public function listadoWada(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoWada();
   }
   public function incluirWada(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->incluirWada($datos);
   }
   public function obtenerWada(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerWada($datos);
   }
   public function modificarWada(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->modificarWada($datos);
   }
   public function eliminarWada(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarWada($datos);
   }
}
