<?php
namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;

class Dashboard extends BaseController
{
   private Database $database;
   protected $model;
   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("dashboard");
      $this->model = new $modelClass($this->database);
   }
   public function obtenerDatosSistema(): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerDatosSistema();
   }
}

//         if ($_POST["accion"] == "estadisticas") {
//             $medallas_data = $o->obtener_medallas_por_mes();
//             $progreso_data = $o->obtener_progreso_semanal();

//             $response = [
//                 'labels_medallas' => $medallas_data['labels'],
//                 'medallas_por_mes' => $medallas_data['medallas'],
//                 'labels_progreso' => $progreso_data['labels'],
//                 'progreso_semanal' => $progreso_data['progreso']
//             ];
//             echo json_encode($response);
//             exit;
//         }
//     }
//     $oi = new Reporte();
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         if (!empty($_POST)) {
//             $accion = $_POST['accion'];
//             if ($accion == 'obtener_reportes') {
//                 $filtros = [
//                     'edadMin' => $_POST['edadMin'] ?? null,
//                     'edadMax' => $_POST['edadMax'] ?? null,
//                     'genero' => $_POST['genero'] ?? null,
//                     'tipoAtleta' => $_POST['tipoAtleta'] ?? null,
//                     'pesoMin' => $_POST['pesoMin'] ?? null,
//                     'pesoMax' => $_POST['pesoMax'] ?? null,
//                     'edadMinEntrenador' => $_POST['edadMinEntrenador'] ?? null,
//                     'edadMaxEntrenador' => $_POST['edadMaxEntrenador'] ?? null,
//                     'gradoInstruccion' => $_POST['gradoInstruccion'] ?? null,
//                     'fechaInicioEventos' => $_POST['fechaInicioEventos'] ?? null,
//                     'fechaFinEventos' => $_POST['fechaFinEventos'] ?? null,
//                     'fechaInicioMensualidades' => $_POST['fechaInicioMensualidades'] ?? null,
//                     'fechaFinMensualidades' => $_POST['fechaFinMensualidades'] ?? null,
//                 ];
//                 $respuesta = $oi->obtener_reportes($_POST['tipoReporte'], $filtros);
//                 header('Content-Type: application/json');
//                 echo json_encode($respuesta);
//                 exit();
//             }
    
//             if ($accion == 'obtener_resultados_competencias') {
//                 $filtros = [
//                     'fechaInicioEventos' => $_POST['fechaInicioEventos'] ?? null,
//                     'fechaFinEventos' => $_POST['fechaFinEventos'] ?? null,
//                 ];
//                 $respuesta = $oi->obtener_resultados_competencias($filtros);
//                 header('Content-Type: application/json');
//                 echo json_encode($respuesta);
//                 exit();
//             }
//             if ($accion === 'obtenerDatosEstadisticos') {
//                 $tipo = $_POST['tipo'] ?? '';
//                 $respuesta = $oi->obtenerDatosEstadisticos($tipo);
//                 header('Content-Type: application/json');
//                 echo json_encode($respuesta);
//                 exit();
//             }
//             if ($accion === 'obtenerProgresoAsistencias') {
//                 $respuesta = $oi->obtenerProgresoAsistenciasMensuales();
//                 header('Content-Type: application/json');
//                 echo json_encode($respuesta);
//                 exit();
//             }
//             if ($accion === 'obtenerCumplimientoWADA') {
//                 $respuesta = $oi->obtenerCumplimientoWADA();
//                 header('Content-Type: application/json');
//                 echo json_encode($respuesta);
//                 exit();
//             }
    
//             if ($accion === 'obtenerVencimientosWADA') {
//                 $respuesta = $oi->obtenerVencimientosWADA();
//                 header('Content-Type: application/json');
//                 echo json_encode($respuesta);
//                 exit();
//             }
    
//     if ($accion == 'obtener_reportes') {
//         $filtros = [
//             'edadMin' => $_POST['edadMin'] ?? null,
//             'edadMax' => $_POST['edadMax'] ?? null,
//             'genero' => $_POST['genero'] ?? null,
//             'pesoMin' => $_POST['pesoMin'] ?? null,
//             'pesoMax' => $_POST['pesoMax'] ?? null,
//         ];
    
//         $reportes = $oi->obtener_reportes($_POST['tipoReporte'], $filtros);
//         $estadisticas = $oi->obtenerEstadisticas($_POST['tipoReporte'], $filtros);
    
//         header('Content-Type: application/json');
//         echo json_encode([
//             "ok" => $reportes["ok"] && $estadisticas["ok"],
//             "reportes" => $reportes["reportes"] ?? [],
//             "estadisticas" => $estadisticas["estadisticas"] ?? [],
//     // Asignar los datos obtenidos a variables para la vista del dashboard
//   
//     $ultimos_atletas = $o->obtener_ultimos_atletas();
//     $ultimas_acciones = $o->obtener_ultimas_acciones();