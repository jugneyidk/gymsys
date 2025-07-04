<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Subs extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Subs");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Eventos", $this->database);
   }
   public function listadoSubs(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoSubs();
   }
   public function incluirSub(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->incluirSub($datos);
   }
   public function modificarSub(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->modificarSub($datos);
   }
   public function eliminarSub(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      $this->requireCsrf();
      return $this->model->eliminarSub($datos);
   }
}
