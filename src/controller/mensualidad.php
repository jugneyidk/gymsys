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
   
}

   //  if ($accion == 'incluir') {
   //      $respuesta = $o->incluir_mensualidad(
   //          $_POST['id_atleta'],
   //          $_POST['monto'],
   //          $_POST['fecha'],
   //          $_POST['detalles'] ?? null
   //      );
   //      echo json_encode($respuesta);
   //  } elseif ($accion == 'eliminar_mensualidad') {
   //      $respuesta = $o->eliminar_mensualidad($_POST["id"]);
   //      echo json_encode($respuesta);
   //  } elseif ($accion == 'listado_mensualidades') {
   //      $respuesta = $o->listado_mensualidades();
   //      echo json_encode($respuesta);
   //  } elseif ($accion == 'listado_deudores') {
   //      $respuesta = $o->listado_deudores();
   //      echo json_encode($respuesta);
   //  } elseif ($accion == 'listado_atletas') {
   //      $respuesta = $o->listado_atletas();
   //      echo json_encode($respuesta);