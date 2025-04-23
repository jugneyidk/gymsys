<?php

namespace Gymsys\Controller;

use Exception;
use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Rolespermisos extends BaseController
{
   protected Database $database;
   private object $model;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $modelClass = $this->getModel("Rolespermisos");
      $this->model = new $modelClass($this->database);
   }

   public function obtenerPermisosModulo(string $modulo)
   {
      if (empty($_SESSION["id_usuario"])) {
         session_destroy();
         ExceptionHandler::throwException("No autorizado", 403, Exception::class);
      }
      $response = $this->model->obtenerPermisosModulo($modulo, $this->database);
      return $response;
   }
   public function obtenerPermisosNav()
   {
      if (empty($_SESSION["id_usuario"])) {
         ExceptionHandler::throwException("No autorizado", 403, Exception::class);
      }
      $response = $this->model->obtenerPermisosNav($this->database);
      return $response;
   }
}

//    if ($permisos["leer"] === 0) {
//       header("Location: .");
//    }
//    if (!empty($_POST)) {
//       $accion = $_POST['accion'];
//       if ($accion == 'listado_roles') {
//          $respuesta = $o->listado_roles();
//          echo json_encode($respuesta);
//       } elseif ($accion == 'incluir') {
//          $valores = [];
//          foreach ($_POST as $campo => $valor) {
//             if (!$_POST[$campo] != "nombre_rol") {
//                $valores[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : 0;
//             }
//          }
//          $respuesta = $o->incluir_rol(
//             $_POST['nombre_rol'],
//             $valores
//          );
//          echo json_encode($respuesta);
//       } elseif ($accion == 'modificar') {
//          $valores = [];
//          foreach ($_POST as $campo => $valor) {
//             if (!$_POST[$campo] != "nombre_rol") {
//                $valores[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : 0;
//             }
//          }
//          $respuesta = $o->modificar_rol(
//             $_POST['id_rol'],
//             $_POST['nombre_rol'],
//             $valores
//          );
//          echo json_encode($respuesta);
//       } elseif ($accion == 'eliminar_rol') {
//          $respuesta = $o->eliminar_rol($_POST['id_rol']);
//          echo json_encode($respuesta);
//       } elseif ($accion == 'consultar_rol') {
//          $respuesta = $o->consultar_rol($_POST['id_rol']);
//          echo json_encode($respuesta);
//       } elseif ($accion == 'asignar_rol') {
//          $respuesta = $o->asignar_rol($_POST['cedula'], $_POST['id_rol_asignar']);
//          echo json_encode($respuesta);
//       } elseif ($accion == 'consultar_rol_usuario') {
//          $respuesta = $o->consultar_rol_usuario($_POST['cedula']);
//          echo json_encode($respuesta);
//       }
//       exit;
//    }
//    require_once("vista/" . $p . ".php");
// } else {
//    echo "pagina en construccion";
// }
