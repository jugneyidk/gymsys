<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}
require_once("modelo/" . $p . ".php");

if (!empty($_POST)) {
    $o = new Entrenador(); // se pone el nombre de la clase del modelo
    if ($_POST["accion"] == "incluir") {
        $response = $o->incluir_entrenador(
            $_POST["nombres"],
            $_POST["apellidos"],
            $_POST["cedula"],
            $_POST["genero"],
            $_POST["fecha_nacimiento"],
            $_POST["lugar_nacimiento"],
            $_POST["estado_civil"],
            $_POST["telefono"],
            $_POST["correo"],
            $_POST["grado_instruccion"]
        );
        echo json_encode($response);
    } elseif ($_POST["accion"] == "modificar") {
        $response = $o->modificar_entrenador(
            $_POST["nombres_modificar"],
            $_POST["apellidos_modificar"],
            $_POST["cedula_modificar"],
            $_POST["genero_modificar"],
            $_POST["fecha_nacimiento_modificar"],
            $_POST["lugar_nacimiento_modificar"],
            $_POST["estado_civil_modificar"],
            $_POST["telefono_modificar"],
            $_POST["correo_modificar"],
            $_POST["grado_instruccion_modificar"]
        );
        echo json_encode($response);
    } elseif ($_POST["accion"] == "obtener_entrenador") {
        $response = $o->obtener_entrenador($_POST["cedula"]);
        echo json_encode($response);
    } elseif ($_POST["accion"] == "listado_entrenadores") {
        $response = $o->listado_entrenador();
        echo json_encode($response);
    }
    exit;
}

if (is_file("vista/" . $p . ".php")) {
    require_once("vista/" . $p . ".php");
} else {
    require_once("comunes/404.php");
}
?>