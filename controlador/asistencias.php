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
    } elseif ($accion == 'listado') {
        $respuesta = $o->listado_asistencias();
        echo json_encode($respuesta);
    } elseif ($accion == 'guardar') {
        $asistencias = json_decode($_POST['asistencias'], true);
        $respuesta = $o->guardar_asistencia($_POST['fecha'], $asistencias);
        echo json_encode($respuesta);
    } elseif ($accion == 'listar_atletas') {
        $respuesta = $o->listar_atletas();
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
