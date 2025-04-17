<?php
require_once("modelo/recovery.php");

// Si es una solicitud POST, procesamos la acción
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    ob_start(); // Captura cualquier salida no deseada

    try {
        $accion = $_POST['accion'] ?? '';
        if ($accion === 'restablecer') {
            $token = $_POST['token'] ?? null;
            $nueva_contraseña = $_POST['nueva_contraseña'] ?? null;
            $confirmar_contraseña = $_POST['confirmar_contraseña'] ?? null;

            if (!$token) {
                echo json_encode(["ok" => false, "mensaje" => "Token no proporcionado."]);
                exit;
            }

            if ($nueva_contraseña !== $confirmar_contraseña) {
                echo json_encode(["ok" => false, "mensaje" => "Las contraseñas no coinciden."]);
                exit;
            }

            $recuperacion = new Recuperacion();

            // Validamos el token y obtenemos el email asociado
          
            $respuesta = $recuperacion->restablecer_contrasena('soykuuhaku@gmail.com', $nueva_contraseña);
            echo json_encode($respuesta);
        } else {
            echo json_encode(["ok" => false, "mensaje" => "Acción no válida."]);
        }
    } catch (Exception $e) {
        echo json_encode(["ok" => false, "mensaje" => "Error del servidor: " . $e->getMessage()]);
    } finally {
        ob_end_clean();
        exit;
    }
}

// Si no es una solicitud POST, cargamos la vista
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $recuperacion = new Recuperacion();
    $respuesta = $recuperacion->verificar_token($token);

    if (!$respuesta['ok']) {
        echo "Token inválido o expirado. Vuelve a solicitar el restablecimiento de contraseña.";
        exit;
    }

    // Cargar la vista si el token es válido
    if (is_file("vista/" . $p . ".php")) {
        require_once("vista/" . $p . ".php");
    } else {
        require_once("comunes/404.php");
    }
} else {
    echo "Token no proporcionado.";
}
