<?php
// Verificar si el modelo existe
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}

// Incluir el modelo
require_once("modelo/" . $p . ".php");

// Verificar si se envió un formulario por POST
if (!empty($_POST)) {
    // Crear una instancia de la clase del modelo
    $o = new WADA();

    // Obtener la acción desde el formulario
    $accion = $_POST['accion'];

    // Ejecutar la acción correspondiente
    if ($accion == 'incluir') {
        $respuesta = $o->incluir_wada(
            $_POST['atleta'],
            $_POST['status'],
            $_POST['inscrito'],
            $_POST['ultima_actualizacion'],
            $_POST['vencimiento']
        );
        echo json_encode($respuesta);
    } else if ($accion == 'listado_wada') {
        $respuesta = $o->listado_wada();
        echo json_encode($respuesta);
    } else if ($accion == 'modificar') {
        $respuesta = $o->modificar_wada(
            $_POST['atleta'],
            $_POST['status'],
            $_POST['inscrito'],
            $_POST['ultima_actualizacion'],
            $_POST['vencimiento']
        );
        echo json_encode($respuesta);
    } else if ($accion == 'obtener_wada') {
        $respuesta = $o->obtener_wada($_POST['atleta']);
        echo json_encode($respuesta);
    } else if ($accion == 'eliminar') {
        $respuesta = $o->eliminar_wada($_POST['atleta']);
        echo json_encode($respuesta);
    }

    // Terminar la ejecución del script después de procesar la solicitud
    exit;
}

// Verificar si la vista existe y cargarla
if (is_file("vista/" . $p . ".php")) {
    require_once("vista/" . $p . ".php");
} else {
    require_once("comunes/404.php");
}
?>
