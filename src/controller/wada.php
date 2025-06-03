<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
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
   }
   public function listadoPorVencer(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoPorVencer();
   }
   public function listadoWada(): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->listadoWada();
   }
   public function incluirWada(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      return $this->model->incluirWada($datos);
   }
   public function obtenerWada(array $datos): array
   {$this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerWada($datos);
   }
   public function modificarWada(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      return $this->model->modificarWada($datos);
   }
   public function eliminarWada(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarWada($datos);
   }
}
