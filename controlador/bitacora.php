<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once ("modelo/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
    $o = new Bitacora();
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        if ($accion == 'listado_bitacora') {
            $respuesta = $o->listado_bitacora();
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir') {
            $valores = [
                "dreportes" => isset($_POST['dreportes']) ? $_POST['dreportes'] : 0,
            ];
            $respuesta = $o->incluir_rol(
                $_POST['nombre'],
                $valores
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
        }
        exit;
    }
    require_once ("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
