<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once ("modelo/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
    $o = new Bitacora();
    require_once ("modelo/permisos.php");
    $permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if ($permisos["leer"] === 0) {
        header("Location: .");
    }
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        if ($accion == 'listado_bitacora') {
            $respuesta = $o->listado_bitacora();
            echo json_encode($respuesta);
        }
        exit;
    }
    require_once ("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
