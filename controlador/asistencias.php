<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once("modelo/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
    $o = new Asistencia();
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
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}


?>
