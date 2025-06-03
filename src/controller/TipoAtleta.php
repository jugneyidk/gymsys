<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class TipoAtleta extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("TipoAtleta");
      $this->model = new $modelClass((object) $database);
      $this->permisos = $this->obtenerPermisos("Atletas", $this->database);
   }
   public function listadoTipoAtletas(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoTipoAtletas();
   }
   public function incluirTipoAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      return $this->model->incluirTipoAtleta($datos);
   }
   public function eliminarTipoAtleta(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarTipoAtleta($datos);
   }
}
