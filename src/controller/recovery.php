<?php
if (!empty($_POST)) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        $accion = $_POST['accion'];
        if ($accion == "recuperar") {
            $email = $_POST['email'];
            $cedula = $_POST['cedula'];
            $recuperacion = new Recuperacion();
            $respuesta = $recuperacion->generar_recuperacion($email, $cedula);
            echo json_encode($respuesta);
            exit;
        }

        if ($accion == "verificar_token") {
            $token = $_POST['token'];
            $recuperacion = new Recuperacion();
            $respuesta = $recuperacion->verificar_token($token);
            echo json_encode($respuesta);
            exit;
        }

        if ($accion == "restablecer") {
            $email = $_POST['email'];
            $nueva_contraseña = $_POST['nueva_contrasena'];
            $recuperacion = new Recuperacion();
            $respuesta = $recuperacion->restablecer_contrasena($email, $nueva_contrasena);
            echo json_encode($respuesta);
            exit;
        }
        echo json_encode(["ok" => false, "mensaje" => "Acción no válida"]);
        exit;
    }
}
// Renderiza la vista HTML si no es una solicitud AJAX
if (is_file("vista/" . $p . ".php")) {
    require_once("vista/" . $p . ".php");
} else {
    require_once("vista/404.php");
}
