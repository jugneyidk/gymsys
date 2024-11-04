<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}
require_once("modelo/" . $p . ".php");
require_once("modelo/permisos.php");
$o = new Entrenador();
$permisos_o = new Permisos();
$permisos = $permisos_o->chequear_permisos();
if ($permisos["leer"] === 0) {
    header("Location: .");
}
if (!empty($_POST)) {
    if ($_POST["accion"] == "incluir") {
        $response = $o->incluir_entrenador($_POST);
        echo json_encode($response);
    } elseif ($_POST["accion"] == "modificar") {
        $password = isset($_POST["modificar_contraseña"]) && $_POST["modificar_contraseña"] === "on" ? $_POST["password_modificar"] : null;
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
            $_POST["grado_instruccion_modificar"],
            $password
        );
        echo json_encode($response);
    } elseif ($_POST["accion"] == "obtener_entrenador") {
        $response = $o->obtener_entrenador($_POST["cedula"]);
        echo json_encode($response);
    } elseif ($_POST["accion"] == "listado_entrenadores") {
        $response = $o->listado_entrenador();
        echo json_encode($response);
    } elseif ($_POST["accion"] == "eliminar") {
        $response = $o->eliminar_entrenador($_POST["cedula"]);
        echo json_encode($response);
    }
    exit;
}

if (is_file("vista/" . $p . ".php")) {
    require_once("vista/" . $p . ".php");
} else {
    require_once("comunes/404.php");
}