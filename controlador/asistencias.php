<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}

require_once("modelo/" . $p . ".php");

if (!empty($_POST)) {
    $o = new Asistencia();
    $accion = $_POST['accion'];

    if ($accion == 'crear') {
        $respuesta = $o->crear_asistencia($_POST['fecha']);
        echo json_encode($respuesta);
    } else if ($accion == 'listado') {
        $respuesta = $o->listado_asistencias();
        echo json_encode($respuesta);
    } else if ($accion == 'guardar') {
        $respuesta = $o->guardar_asistencia($_POST['fecha'], $_POST['asistencias']);
        echo json_encode($respuesta);
    }
    exit;
}

if (is_file("vista/" . $p . ".php")) {
    require_once("vista/" . $p . ".php");
} else {
    require_once("comunes/404.php");
}
?>