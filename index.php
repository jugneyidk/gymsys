<?php
require 'vendor/autoload.php';
session_start();
if (!empty($_GET['p'])) {
  $p = $_GET['p'];
  if (!isset($_SESSION['id_usuario']) && ($p != "login")) {
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
  require_once("comunes/carga.php");
} else {
  require_once("vista/404.php");
}
