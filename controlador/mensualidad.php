<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}

require_once("modelo/" . $p . ".php");

if (!empty($_POST)) {
    $o = new Mensualidad(); // Nombre de la clase del modelo
    $accion = $_POST['accion'];

    if ($accion == 'incluir') {
        $respuesta = $o->incluir_mensualidad(
            $_POST['id_atleta'], 
            $_POST['tipo_mensualidad'], 
            $_POST['cobro'], 
            $_POST['pago'], 
            $_POST['fecha']
        );
        echo json_encode($respuesta);
    } else if ($accion == 'listado_mensualidad') {
        $respuesta = $o->listado_mensualidad();
        echo json_encode($respuesta);
    } else if ($accion == 'modificar') {
        $respuesta = $o->modificar_mensualidad(
            $_POST['id_mensualidad'],
            $_POST['id_atleta'], 
            $_POST['tipo_mensualidad'], 
            $_POST['cobro'], 
            $_POST['pago'], 
            $_POST['fecha']
        );
        echo json_encode($respuesta);
    } else if ($accion == 'eliminar') {
        $respuesta = $o->eliminar_mensualidad($_POST['id_mensualidad']);
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
