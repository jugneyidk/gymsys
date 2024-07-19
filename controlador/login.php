<?php
if (!is_file("modelo/" . $p . ".php")) {
  echo "No existe el modelo.";
  exit;
}
require_once ("modelo/" . $p . ".php");
if (isset($_SESSION['id_usuario'])) {
  header("location: .");
}
if (!empty($_POST)) {
  session_destroy();
  $o = new Login();
  $accion = $_POST["accion"];
  if ($accion == "login") {
    $respuesta = $o->iniciar_sesion($_POST["id_usuario"], $_POST["password"]);
    echo json_encode($respuesta);
  }
  exit;
}
if (is_file("vista/" . $p . ".php")) {
  require_once ("vista/" . $p . ".php");
} else {
  require_once ("comunes/404.php");
}