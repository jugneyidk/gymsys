<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\ExceptionHandler;

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
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
   }
   public function obtenerEstadisticas()
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerEstadisticas();
   }
}

   //  if (!empty($_POST)) {
   //      $accion = $_POST['accion'];
        
       
   //      if ($accion === 'obtener_resultados_competencias') {
   //          $filtros = [
   //              'fechaInicioEventos' => $_POST['fechaInicioEventos'] ?? null,
   //              'fechaFinEventos' => $_POST['fechaFinEventos'] ?? null
   //          ];
   //          $respuesta = $o->obtener_resultados_competencias($filtros);
   //          header('Content-Type: application/json');
   //          echo json_encode($respuesta);
   //          exit();
   //      }

   //      if ($accion === 'obtenerDatosEstadisticos') {
   //          $tipo = $_POST['tipo'] ?? '';
   //          $respuesta = $o->obtenerDatosEstadisticos($tipo);
   //          header('Content-Type: application/json');
   //          echo json_encode($respuesta);
   //          exit();
   //      }

   //      if ($accion === 'obtenerProgresoAsistencias') {
   //          $respuesta = $o->obtenerProgresoAsistenciasMensuales();
   //          header('Content-Type: application/json');
   //          echo json_encode($respuesta);
   //          exit();
   //      }

   //      if ($accion === 'obtenerCumplimientoWADA') {
   //          $respuesta = $o->obtenerCumplimientoWADA();
   //          header('Content-Type: application/json');
   //          echo json_encode($respuesta);
   //          exit();
   //      }

   //      if ($accion === 'obtenerVencimientosWADA') {
   //          $respuesta = $o->obtenerVencimientosWADA();
   //          header('Content-Type: application/json');
   //          echo json_encode($respuesta);
   //          exit();
   //      }
   //       if ($accion === 'obtener_reporte_individual') {
      
   //          $idAtleta = $_POST['idAtleta']; 
   //          $filtros = [
   //              'idAtleta' => $idAtleta,
             
   //          ];
   //          $respuesta = $o->obtener_reporte_individual($filtros);  
   //          header('Content-Type: application/json');
   //          echo json_encode($respuesta);
   //          exit();
   //      }
        
   //       elseif ($_POST['accion'] === 'obtener_reportes') {
   //          $filtros = [
   //              'edadMin' => $_POST['edadMin'] ?? null,
   //              'edadMax' => $_POST['edadMax'] ?? null,
   //              'genero' => $_POST['genero'] ?? null,
   //              'tipoAtleta' => $_POST['tipoAtleta'] ?? null,
   //              'pesoMin' => $_POST['pesoMin'] ?? null,
   //              'pesoMax' => $_POST['pesoMax'] ?? null,
   //              'edadMinEntrenador' => $_POST['edadMinEntrenador'] ?? null,
   //              'edadMaxEntrenador' => $_POST['edadMaxEntrenador'] ?? null,
   //              'gradoInstruccion' => $_POST['gradoInstruccion'] ?? null,
   //              'fechaInicioEventos' => $_POST['fechaInicioEventos'] ?? null,
   //              'fechaFinEventos' => $_POST['fechaFinEventos'] ?? null,
   //              'fechaInicioMensualidades' => $_POST['fechaInicioMensualidades'] ?? null,
   //              'fechaFinMensualidades' => $_POST['fechaFinMensualidades'] ?? null
   //          ];
        
   //          $reportes = $o->obtener_reportes($_POST['tipoReporte'], $filtros);
   //          $estadisticas = $o->obtenerEstadisticas($_POST['tipoReporte'], $filtros);
        
   //          header('Content-Type: application/json');
   //          echo json_encode([
   //              "ok" => $reportes["ok"] && $estadisticas["ok"],
   //              "reportes" => $reportes["reportes"] ?? [],
   //              "estadisticas" => $estadisticas["estadisticas"] ?? []
   //          ])