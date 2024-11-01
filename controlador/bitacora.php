<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
require_once("modelo/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
    $o = new Bitacora();
    require_once("modelo/permisos.php");
    $permisos_o = new Permisos();
    $permisos = $permisos_o->chequear_permisos();
    if (empty($permisos) || $permisos["leer"] === 0) {
        header("Location: .");
        exit;
    }
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        switch ($accion) {
            case 'listado_bitacora':
                $respuesta = $o->listado_bitacora();
                echo json_encode($respuesta);
                break;
            case 'consultar_accion':
                $respuesta = $o->consultar_accion($_POST['id_accion']);
                echo json_encode($respuesta);
                break;
            default:

                break;
        }
        exit;
    }
    require_once("vista/" . $p . ".php");
} else {
    echo "pagina en construccion";
}
