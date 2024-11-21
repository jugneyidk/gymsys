<?php
if (!is_file("modelo/" . $p . ".php")) {
  echo "No existe el modelo.";
  exit;
}
if (!empty($_GET["id"])) {
  $o = new PerfilAtleta();
  $permisos_o = new Permisos();
  $cedula = $_GET['id'];
  $atleta = $o->obtener_atleta($cedula);
} else {
  header("location: .");
}
if (is_file("vista/" . $p . ".php")) {
  require_once("vista/" . $p . ".php");
} else {
  require_once("comunes/404.php");
}
