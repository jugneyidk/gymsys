<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
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
   }
   public function listadoCategorias(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoCategorias();
   }
   public function incluirCategoria(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->incluirCategoria($datos);
   }
   public function modificarCategoria(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->modificarCategoria($datos);
   }
   public function eliminarCategoria(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->eliminarCategoria($datos);
   }
}
