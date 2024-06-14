<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}
require_once ("modelo/" . $p . ".php");
if (!empty($_POST)) {
    $o = new Entrenador(); // se pone el nombre de la clase del modelo
    if ($_POST["accion"] == "incluir") { 
        $response = $o->incluir_entrenador($_POST["nombres"], $_POST["apellidos"], $_POST["cedula"], $_POST["genero"], $_POST["fecha_nacimiento"], $_POST["lugar_nacimiento"], $_POST["estado_civil"], $_POST["telefono"], $_POST["correo_electronico"], $_POST["grado_instruccion"]); // incluir con sus parametros
        echo json_encode($response);
    } else if($_POST["accion"] == "listado_entrenadores"){
        $response = $o->listado_entrenador(); // listado
        echo json_encode($response);
    }
    exit;
}
if (is_file("vista/" . $p . ".php")) {
    require_once ("vista/" . $p . ".php");
} else {
    require_once ("comunes/404.php");
} 