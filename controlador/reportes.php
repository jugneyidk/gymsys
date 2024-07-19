<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once("modelo/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
    $o = new Reporte();
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        if ($accion == 'obtener_reportes') {
            $respuesta = $o->obtener_reportes($_POST['tipoReporte'], $_POST['fechaInicio'], $_POST['fechaFin']);
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}

?>