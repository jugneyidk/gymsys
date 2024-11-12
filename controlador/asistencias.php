<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
if (is_file("vista/" . $p . ".php")) {
    $o = new Asistencia();
    $permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if ($permisos["leer"] === 0) {
        header("Location: .");
    }
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        if ($accion == 'obtener_atletas') {
            $respuesta = $o->obtener_atletas();
            echo json_encode($respuesta);
        } elseif ($accion == 'guardar_asistencias') {
            $respuesta = $o->guardar_asistencias($_POST['fecha'], $_POST['asistencias']);
            echo json_encode($respuesta);
        } elseif ($accion == 'obtener_asistencias') {
            $respuesta = $o->obtener_asistencias($_POST['fecha']);
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once ("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}