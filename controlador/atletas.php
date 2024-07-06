<?php
if (!is_file("modelo/" . $p . ".php")) {
  echo "Falta definir la clase " . $p;
  exit;
}
require_once("modelo/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
  $o = new Atleta();
  //$permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion']; 
    if ($accion == 'listado_atleta') {
      $respuesta = $o->listado_atleta();
      echo json_encode($respuesta);
    }  elseif ($accion == 'incluir') {
        $respuesta = $o->incluir_atleta(
        $_POST['nombres'], 
        $_POST['apellidos'], 
        $_POST['cedula'], 
        $_POST['genero'], 
        $_POST['fecha_nacimiento'], 
        $_POST['lugar_nacimiento'], 
        $_POST['peso'], 
        $_POST['altura'], 
        $_POST['tipo_atleta'], 
        $_POST['estado_civil'], 
        $_POST['telefono'], 
        $_POST['correo'], 
        $_POST['entrenador_asignado']);
      echo json_encode($respuesta);
    }
    elseif ($accion == 'modificar') {
      $respuesta = $o->modificar_atleta(
        $_POST['nombres'], 
        $_POST['apellidos'],  
        $_POST['cedula'], 
        $_POST['genero'], 
        $_POST['fecha_nacimiento'], 
        $_POST['lugar_nacimiento'], 
        $_POST['peso'], 
        $_POST['altura'], 
        $_POST['tipo_atleta'], 
        $_POST['estado_civil'], 
        $_POST['telefono'], 
        $_POST['correo'], 
        $_POST['entrenador_asignado']
        );
      echo json_encode($respuesta);
    }
    elseif ($accion == 'eliminar') {
      $respuesta = $o->eliminar($_POST['cedula']);
      echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  echo "pagina en construccion";
}

/*$_POST['nombre_representante'], 
        $_POST['telefono_representante']*/
















