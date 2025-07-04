<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Representantes extends BaseController
{
   private Database $database;
   private object $model;
   private array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Representantes");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Atletas", $this->database);
   }
   public function listadoRepresentantes(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoRepresentantes();
   }
   public function incluirRepresentante(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      return $this->model->incluirRepresentante($datos);
   }
   public function modificarRepresentante(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      return $this->model->modificarRepresentante($datos);
   }
   public function eliminarRepresentante(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarRepresentante($datos);
   }
   public function obtenerRepresentante(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerRepresentante($datos);
   }
}
