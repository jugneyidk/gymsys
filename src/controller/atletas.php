<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Atletas extends BaseController
{
   private Database $database;
   private object $model;
   private array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Atletas");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Atletas", $this->database);
   }
   public function listadoAtletas(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoAtletas();
   }
   public function incluirAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->incluirAtleta($datos);
   }
   public function modificarAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->modificarAtleta($datos);
   }
   public function eliminarAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->eliminarAtleta($datos);
   }
   public function obtenerAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      $this->requireCsrf();
      return $this->model->obtenerAtleta($datos);
   }
}
