<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "Falta definir la clase " . $p;
    exit;
}
$o = new Notificaciones();
if ($_GET["accion"] ?? '' === "crear_notificaciones") {
    $respuesta = $o->crear_notificaciones();
    echo json_encode($respuesta);
    exit;
}
if (!empty($_POST)) {
    if ($_SESSION["id_usuario"] != $_POST["id_usuario"]) {
        header("HTTP/1.1 403 Forbidden");
        exit;
    }
    if (!isset($_POST["accion"])) {
        $respuesta = $o->obtener_notificaciones($_POST['id_usuario']);
        echo json_encode($respuesta);
    } elseif ($_POST["accion"] == "marcar_leida") {
        $respuesta = $o->marcar_leida($_POST['id_notificacion']);
        echo json_encode($respuesta);
    } elseif ($_POST["accion"] == "marcar_todo_leido") {
        $respuesta = $o->marcar_todo_leido($_POST['id_usuario']);
        echo json_encode($respuesta);
    } elseif ($_POST["accion"] == "ver_todas_notificaciones") {
        $respuesta = $o->ver_todas_notificaciones($_POST['id_usuario'], $_POST["pagina"]);
        echo json_encode($respuesta);
    }
    exit;
}
