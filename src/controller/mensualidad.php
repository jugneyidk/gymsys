<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Mensualidad extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Mensualidad");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Mensualidad", $this->database);
   }
   public function listadoMensualidades(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoMensualidades();
   }
   public function listadoDeudores(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoDeudores();
   }
   public function incluirMensualidad(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->incluirMensualidad($datos);
   }
   public function eliminarMensualidad(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->eliminarMensualidad($datos);
   }
}
