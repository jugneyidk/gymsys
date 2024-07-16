<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
  }
  require_once("modelo/" . $p . ".php");
  if (is_file("vista/" . $p . ".php")) {
    $o = new Atleta();
    if (!empty($_POST)) {
        $accion = $_POST['accion']; 
        if ($accion == 'listado_atleta') {
            $respuesta = $o->listado_atleta();
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir') {
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
                $_POST['entrenador_asignado']
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'modificar') {
            $respuesta = $o->modificar_atleta(
                $_POST['nombres_modificar'], 
                $_POST['apellidos_modificar'], 
                $_POST['cedula_modificar'], 
                $_POST['genero_modificar'], 
                $_POST['fecha_nacimiento_modificar'], 
                $_POST['lugar_nacimiento_modificar'], 
                $_POST['peso_modificar'], 
                $_POST['altura_modificar'], 
                $_POST['tipo_atleta_modificar'], 
                $_POST['estado_civil_modificar'], 
                $_POST['telefono_modificar'], 
                $_POST['correo_modificar'], 
                $_POST['entrenador_asignado_modificar']
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'eliminar') {
            $respuesta = $o->eliminar($_POST['cedula']);
            echo json_encode($respuesta);
        } elseif ($accion == 'obtener_atleta') {
            $respuesta = $o->obtener_atleta($_POST['cedula']);
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once("vista/" . $p . ".php");
  } else {
    echo "pagina en construccion";
  }
  
?>
