<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
if (is_file("vista/" . $p . ".php")) {
    $o = new Roles();
    $permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if ($permisos["leer"] === 0) {
        header("Location: .");
    }
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        if ($accion == 'listado_roles') {
            $respuesta = $o->listado_roles();
            echo json_encode($respuesta);
        } elseif ($accion == 'incluir') {
            $valores = [];
            foreach ($_POST as $campo => $valor) {
                if (!$_POST[$campo] != "nombre_rol") {
                    $valores[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : 0;
                }
            }
            $respuesta = $o->incluir_rol(
                $_POST['nombre_rol'],
                $valores
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'modificar') {
            $valores = [];
            foreach ($_POST as $campo => $valor) {
                if (!$_POST[$campo] != "nombre_rol") {
                    $valores[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : 0;
                }
            }
            $respuesta = $o->modificar_rol(
                $_POST['id_rol'],
                $_POST['nombre_rol'],
                $valores
            );
            echo json_encode($respuesta);
        } elseif ($accion == 'eliminar_rol') {
            $respuesta = $o->eliminar_rol($_POST['id_rol']);
            echo json_encode($respuesta);
        } elseif ($accion == 'consultar_rol') {
            $respuesta = $o->consultar_rol($_POST['id_rol']);
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
