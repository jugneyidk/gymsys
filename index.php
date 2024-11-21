<?php
require 'vendor/autoload.php';
date_default_timezone_set('America/Caracas');
session_start();
if (!empty($_GET['p'])) {
  $p = $_GET['p'];
  if (!isset($_SESSION['id_usuario']) && ($p != "login") && ($p !="perfil_atleta") && ($p !="carnet")) {
    header("Location: ?p=login");
  }
} else {
  if (isset($_SESSION['id_usuario'])) {
    $p = "dashboard";
  } else {
    $p = "landing";
  }
}
if (is_file("controlador/" . $p . ".php")) {
  require_once("controlador/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
require_once("comunes/carga.php");