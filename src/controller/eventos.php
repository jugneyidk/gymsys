<?php

namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
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
      $this->permisos = Rolespermisos::obtenerPermisosModulo("Eventos", $this->database);
      if (empty($this->permisos)) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \Exception::class);
      }
   }
   public function incluirEvento(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->incluirEvento($datos);
   }
   public function eliminarEvento(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->eliminarEvento($datos);
   }
   public function modificarEvento(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->modificarEvento($datos);
   }
   public function registrarResultados(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->registrarResultados($datos);
   }
   public function modificarResultados(array $datos): array
   {
      $this->validarMetodoRequest("POST");
      return $this->model->modificarResultados($datos);
   }
   public function cerrarEvento(array $datos): array
   {
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
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerCompetencia($datos);
   }
   public function obtenerResultadosCompetencia(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->obtenerResultadosCompetencia($datos);
   }
   public function listadoAtletasInscritos(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoAtletasInscritos($datos);
   }
   public function listadoAtletasDisponibles(array $datos): array
   {
      $this->validarMetodoRequest("GET");
      return $this->model->listadoAtletasDisponibles($datos);
   }
}

         // case 'incluir_evento':
         //    $respuesta = $o->incluir_evento($_POST);
         //    echo json_encode($respuesta);
         //    break;

         // case 'incluir_categoria':
         //    $respuesta = $o->incluir_categoria(
         //       $_POST['nombre'],
         //       $_POST['pesoMinimo'],
         //       $_POST['pesoMaximo']
         //    );
         //    echo json_encode($respuesta);
         //    break;

         // case 'incluir_subs':
         //    $respuesta = $o->incluir_subs(
         //       $_POST['nombre'],
         //       $_POST['edadMinima'],
         //       $_POST['edadMaxima']
         //    );
         //    echo json_encode($respuesta);
         //    break;
         // case 'listado_categoria':
         //    $respuesta = $o->listado_categoria();
         //    echo json_encode($respuesta);
         //    break;

         // case 'inscribir_atletas':
         //    $id_competencia = $_POST['id_competencia'] ?? null;
         //    $atletas = $_POST['atletas'] ?? [];
         //    if (!$id_competencia || empty($atletas)) {
         //       echo json_encode(["ok" => false, "mensaje" => "Datos insuficientes para inscribir."]);
         //       exit;
         //    }
         //    $respuesta = $o->inscribir_atletas($id_competencia, $atletas);
         //    echo json_encode($respuesta);
         //    break;

         // case 'registrar_resultados':
         //    $respuesta = $o->registrar_resultados(
         //       $_POST['id_competencia'],
         //       $_POST['id_atleta'],
         //       $_POST['arranque'],
         //       $_POST['envion'],
         //       $_POST['medalla_arranque'],
         //       $_POST['medalla_envion'],
         //       $_POST['medalla_total'],
         //       $_POST['total']
         //    );
         //    echo json_encode($respuesta);
         //    break;

         // case 'cerrar_evento':
         //    $id_competencia = $_POST['id_competencia'];
         //    $respuesta = $o->cerrar_evento($id_competencia);
         //    echo json_encode($respuesta);
         //    break;

         // case 'modificar_resultados':
         //    $respuesta = $o->modificar_resultados(
         //       $_POST['id_competencia'],
         //       $_POST['id_atleta'],
         //       $_POST['arranque'],
         //       $_POST['envion'],
         //       $_POST['medalla_arranque'],
         //       $_POST['medalla_envion'],
         //       $_POST['medalla_total'],
         //       $_POST['total']
         //    );
         //    echo json_encode($respuesta);
         //    break;

         // case 'listado_eventos_anteriores':
         //    $respuesta = $o->listado_eventos_anteriores();
         //    echo json_encode($respuesta);
         //    break;

         // case 'obtener_competencia':
         //    $id_competencia = $_POST['id_competencia'];
         //    $respuesta = $o->obtenerCompetencia($id_competencia);
         //    echo json_encode($respuesta);
         //    break;
         // case 'obtener_resultados_competencia':
         //    $id_competencia = $_POST['id_competencia'];
         //    $respuesta = $o->obtener_resultados_competencia($id_competencia);
         //    echo json_encode($respuesta);
         //    break;

         // case 'modificar_competencia':
         //    $respuesta = $o->modificarCompetencia(
         //       $_POST['id_competencia'],
         //       $_POST['nombre'],
         //       $_POST['lugar_competencia'],
         //       $_POST['fecha_inicio'],
         //       $_POST['fecha_fin'],
         //       $_POST['categoria'],
         //       $_POST['subs'],
         //       $_POST['tipo_competencia']
         //    );
         //    echo json_encode($respuesta);
         //    break;

         // case 'eliminar_evento':
         //    $id_competencia = $_POST['id_competencia'];
         //    $respuesta = $o->eliminar_evento($id_competencia);
         //    echo json_encode($respuesta);
         //    break;

         // case 'listado_atletas_disponibles':
         //    $id_categoria = $_POST['id_categoria'];
         //    $id_sub = $_POST['id_sub'];
         //    $id_competencia = $_POST['id_competencia'];
         //    $respuesta = $o->listado_atletas_disponibles($id_categoria, $id_sub, $id_competencia);
         //    echo json_encode($respuesta);
         //    break;

         // case 'modificar_tipo':
         //    $respuesta = $o->modificar_tipo($_POST['id_tipo'], $_POST['nombre']);
         //    echo json_encode($respuesta);
         //    break;

         // case 'eliminar_sub':
         //    $respuesta = $o->eliminar_sub($_POST['id_sub']);
         //    echo json_encode($respuesta);
         //    break;

         // case 'modificar_sub':
         //    $respuesta = $o->modificar_sub(
         //       $_POST['id_sub'],
         //       $_POST['nombre'],
         //       $_POST['edadMinima'],
         //       $_POST['edadMaxima']
         //    );
         //    echo json_encode($respuesta);
         //    break;

         // case 'modificar_categoria':
         //    $respuesta = $o->modificar_categoria(
         //       $_POST['id_categoria'],
         //       $_POST['nombre'],
         //       $_POST['pesoMinimo'],
         //       $_POST['pesoMaximo']
         //    );
         //    echo json_encode($respuesta);
         //    break;

         // case 'eliminar_categoria':
         //    $respuesta = $o->eliminar_categoria($_POST['id_categoria']);
         //    echo json_encode($respuesta);
         //    break;
         // case 'modificar_resultados':
         //    $respuesta = $o->modificar_resultados(
         //       $_POST['id_competencia'],
         //       $_POST['id_atleta'],
         //       $_POST['arranque'],
         //       $_POST['envion'],
         //       $_POST['medalla_arranque'],
         //       $_POST['medalla_envion'],
         //       $_POST['medalla_total'],
         //       $_POST['total']
         //    );
         //    echo json_encode($respuesta);