<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}
$o = new Entrenador();
$permisos_o = new Permisos();
$permisos = $permisos_o->chequear_permisos();
if ($permisos["leer"] === 0) {
    header("Location: .");
}
if (!empty($_POST)) {
    if ($_POST["accion"] == "incluir") {
        unset($_POST["modificar_contraseña"]);
        $response = $o->incluir_entrenador($_POST);
        echo json_encode($response);
    } elseif ($_POST["accion"] == "modificar") {
        $_POST["password"] = isset($_POST["modificar_contraseña"]) && $_POST["modificar_contraseña"] === "on" ? $_POST["password"] : null;
        $response = $o->modificar_entrenador($_POST);
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