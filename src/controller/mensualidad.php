<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
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
      $this->permisos = Rolespermisos::obtenerPermisosModulo("Mensualidad", $this->database);
      if (empty($this->permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
   }
   public function incluirMensualidad(array $datos): array
   {
      return $this->model->incluirMensualidad($datos);
   }
   public function listadoMensualidades(): array
   {
      return $this->model->listadoMensualidades();
   }
   public function listadoDeudores(): array
   {
      return $this->model->listadoDeudores();
   }
   public function eliminarMensualidad(array $datos): array
   {
      return $this->model->eliminarMensualidad($datos);
   }
}
