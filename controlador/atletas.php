<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
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
            $campos_null = ['cedula_representante', 'telefono_representante', 'nombre_representante', 'parentesco_representante'];
            foreach ($campos_null as $campo) {
                if (isset($_POST[$campo]) && $_POST[$campo] === '') {
                    $_POST[$campo] = null;
                }
            }
            $respuesta = $o->incluir_atleta($_POST);
            echo json_encode($respuesta);
        } elseif ($accion == 'modificar') {
            $_POST["password"] = isset($_POST["modificar_contraseña"]) && $_POST["modificar_contraseña"] === "on" ? $_POST["password"] : null;
            $respuesta = $o->modificar_atleta($_POST);
            echo json_encode($respuesta);
        } elseif ($accion == 'eliminar') {
            $respuesta = $o->eliminar_atleta($_POST['cedula']);
            echo json_encode($respuesta);
        } elseif ($accion == 'obtener_atleta') {
            $respuesta = $o->obtener_atleta($_POST['cedula']);
            echo json_encode($respuesta);
        } elseif ($accion == 'obtener_entrenadores') {
            $respuesta = $o->obtenerEntrenadores();
            echo json_encode($respuesta);
            exit;
        } elseif ($accion == 'obtener_tipos_atleta') {
            $respuesta = $o->obtenerTiposAtleta();
            echo json_encode($respuesta);
            exit;
        } elseif ($accion == 'registrar_tipo_atleta') {
            $respuesta = $o->registrarTipoAtleta(
                $_POST['nombre_tipo_atleta'],
                $_POST['tipo_cobro']
            );
            echo json_encode($respuesta);
            exit;
        } elseif ($accion == 'eliminar_tipo_atleta') {
            $respuesta = $o->eliminar_tipo_atleta(
                $_POST['id_tipo'],
            );
            echo json_encode($respuesta);
            exit;
        }

        exit;
    }
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}

?>