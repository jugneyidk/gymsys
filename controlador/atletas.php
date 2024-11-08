<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once ("modelo/" . $p . ".php");
require_once ("modelo/permisos.php");
if (is_file("vista/" . $p . ".php")) { 
    $o = new Atleta();
    $permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if ($permisos["leer"] === 0) {
        header("Location: .");
    } 
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
                $_POST['entrenador_asignado'],
                $_POST['password'],
                $_POST['cedula_representante'],
                $_POST['nombre_representante'],
                $_POST['telefono_representante'],
                $_POST['parentesco_representante']
            );
            
            echo json_encode($respuesta);
        } elseif ($accion == 'modificar') {
            $modificar_contraseña = isset($_POST['modificar_contraseña']) && $_POST['modificar_contraseña'] === 'on';
            $password_modificar = $modificar_contraseña ? $_POST['password_modificar'] : null;
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
                $_POST['entrenador_asignado_modificar'],
                $modificar_contraseña,
                $password_modificar
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'eliminar') {
            $respuesta = $o->eliminar_atleta($_POST['cedula']);
            echo json_encode($respuesta);
        } elseif ($accion == 'obtener_atleta') {
            $respuesta = $o->obtener_atleta($_POST['cedula']);
            echo json_encode($respuesta);
        }elseif ($accion == 'obtener_entrenadores') {
            $respuesta = $o->obtenerEntrenadores();
            echo json_encode($respuesta);
            exit;
        }elseif ($accion == 'obtener_tipos_atleta') {
            $respuesta = $o->obtenerTiposAtleta();
            echo json_encode($respuesta);
            exit;
        }elseif ($accion == 'registrar_tipo_atleta') {
            $respuesta = $o->registrarTipoAtleta(
                $_POST['nombre_tipo_atleta'],
                $_POST['tipo_cobro']
            );
            echo json_encode($respuesta);
            exit;
        }        
        
        exit;
    }
    require_once ("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}

?>