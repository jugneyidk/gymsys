<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Eventos extends BaseController
{
   private Database $database;
   private object $model;
   protected array $permisos;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Eventos");
      $this->model = new $modelClass((object) $this->database);
      $this->permisos = $this->obtenerPermisos("Eventos", $this->database);
   }
   public function incluirEvento(array $datos): array
   {
      $this->validarPermisos($this->permisos, "crear");
      $this->validarMetodoRequest("POST");
      return $this->model->incluirEvento($datos);
   }
   public function eliminarEvento(array $datos): array
   {
      $this->validarPermisos($this->permisos, "eliminar");
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarEvento($datos);
   }
   public function modificarEvento(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      return $this->model->modificarEvento($datos);
   }
   public function inscribirAtletas(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      return $this->model->inscribirAtletas($datos);
   }
   public function registrarResultados(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      return $this->model->registrarResultados($datos);
   }
   public function modificarResultados(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      return $this->model->modificarResultados($datos);
   }
   public function cerrarEvento(array $datos): array
   {
      $this->validarPermisos($this->permisos, "actualizar");
      $this->validarMetodoRequest("POST");
      return $this->model->cerrarEvento($datos);
   }
   public function listadoEventos(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoEventos();
   }
   public function listadoEventosAnteriores(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoEventosAnteriores();
   }
   public function obtenerCompetencia(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerCompetencia($datos);
   }
   public function obtenerResultadosCompetencia(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerResultadosCompetencia($datos);
   }
   public function listadoAtletasInscritos(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->listadoAtletasInscritos($datos);
   }
   public function listadoAtletasDisponibles(array $datos): array
   {
      $this->validarPermisos($this->permisos, "leer");
      $this->validarMetodoRequest("GET");
      return $this->model->listadoAtletasDisponibles($datos);
   }
}
