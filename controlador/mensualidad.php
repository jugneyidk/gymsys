<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}

require_once ("modelo/" . $p . ".php");
require_once ("modelo/permisos.php");
$permisos_o = new Permisos();
$permisos = $permisos_o->chequear_permisos();
if ($permisos["leer"] === 0) {
    header("Location: .");
}
if (!empty($_POST)) {
    $o = new Mensualidad();
    $accion = $_POST['accion'];
    if ($accion == 'incluir') {
        $respuesta = $o->incluir_mensualidad(
            $_POST['id_atleta'],
            $_POST['monto'],
            $_POST['fecha']
        );
        echo json_encode($respuesta);
    } elseif ($accion == 'listado_mensualidades') {
        $respuesta = $o->listado_mensualidades();
        echo json_encode($respuesta);
    } elseif ($accion == 'listado_deudores') {
        $respuesta = $o->listado_deudores();
        echo json_encode($respuesta);
    } elseif ($accion == 'listado_atletas') {
        $respuesta = $o->listado_atletas();
        echo json_encode($respuesta);
    }
    exit;
}

if (is_file("vista/" . $p . ".php")) {
    require_once ("vista/" . $p . ".php");
} else {
    require_once ("comunes/404.php");
}
?>