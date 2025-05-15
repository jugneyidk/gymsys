<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;

class Categorias extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Categorias");
      $this->model = new $modelClass((object) $database);
      $this->permisos = $this->obtenerPermisos("Eventos", $this->database);
      if ($this->permisos['leer'] == 0) ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }
   public function listadoCategorias(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoCategorias();
   }
   public function incluirCategoria(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->incluirCategoria($datos);
   }
   public function modificarCategoria(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->modificarCategoria($datos);
   }
   public function eliminarCategoria(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarCategoria($datos);
   }
}
