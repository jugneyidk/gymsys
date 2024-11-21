<?php
if (!empty($_POST)) {
    require_once("modelo/Login.php");
    $accion = $_POST['accion'];

    if ($accion == "recuperar") {
        $email = $_POST['email'];
        $login = new Login();
        $respuesta = $login->recuperar_contraseña($email);
        echo json_encode($respuesta);
        exit;
    }
}
if (is_file("vista/" . $p . ".php")) {
  
  require_once("vista/" . $p . ".php");
} else {
  require_once("comunes/404.php");
}
?>
