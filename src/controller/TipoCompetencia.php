<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;

class TipoCompetencia extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("TipoCompetencia");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Eventos", $this->database);
      if ($this->permisos['leer'] == 0) ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
   }
   public function listadoTipos(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoTipos();
   }
   public function incluirTipo(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->incluirTipo($datos);
   }
   public function modificarTipo(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->modificarTipo($datos);
   }
   public function eliminarTipo(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarTipo($datos);
   }
}
