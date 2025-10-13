<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Reportes extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Reportes");
      $this->model = new $modelClass((object) $database);
      $this->permisos = Rolespermisos::obtenerPermisosModulo("Reportes", $this->database);
      if (empty($this->permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", \InvalidArgumentException::class);
      }
   }

   public function obtenerResultadosCompetencias(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      $keys = ["fechaInicioEventos", "fechaFinEventos"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      return $this->model->obtenerResultadosCompetencias($arrayFiltrado);
   }

   public function obtenerDatosEstadisticos(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerDatosEstadisticos($datos);
   }

   public function obtenerProgresoAsistencias(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerProgresoAsistencias();
   }

   public function obtenerCumplimientoWADA(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerCumplimientoWADA();
   }

   public function obtenerReporteIndividual(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->obtenerReporteIndividual($datos);
   }

   public function obtenerReportes(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->obtenerReportes($datos);
   }
}
