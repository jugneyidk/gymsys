<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}
require_once ("modelo/" . $p . ".php");
require_once ("modelo/permisos.php");

$permisos_o = new Permisos();
$permisos = $permisos_o->chequear_permisos();
if (!empty($_POST)) {
    $o = new WADA();
    $accion = $_POST['accion'];

    if ($accion == 'incluir') {
        $respuesta = $o->incluir_wada(
            $_POST['atleta'],
            $_POST['status'],
            $_POST['inscrito'],
            $_POST['ultima_actualizacion'],
            $_POST['vencimiento']
        );
        echo json_encode($respuesta);
    } elseif ($accion == 'listado_wada') {
        $respuesta = $o->listado_wada();
        echo json_encode($respuesta);
    } elseif ($accion == 'modificar') {
        $respuesta = $o->modificar_wada(
            $_POST['atleta'],
            $_POST['status'],
            $_POST['inscrito'],
            $_POST['ultima_actualizacion'],
            $_POST['vencimiento']
        );
        echo json_encode($respuesta);
    } elseif ($accion == 'obtener_wada') {
        $respuesta = $o->obtener_wada($_POST['atleta']);
        echo json_encode($respuesta);
    } elseif ($accion == 'eliminar') {
        $respuesta = $o->eliminar_wada($_POST['atleta']);
        echo json_encode($respuesta);
    } elseif ($accion == 'listado_atletas') {
        $respuesta = $o->listado_atletas();
        echo json_encode($respuesta);
    } elseif ($accion == 'obtener_proximos_vencer') {
        $respuesta = $o->obtener_proximos_vencer();
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