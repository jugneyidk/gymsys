<?php
$permisos_o = new Permisos();
$permisos = $permisos_o->chequear_permisos_completos();
if (!isset($_SESSION["rol"]) || !isset($_SESSION["id_usuario"]) || !$permisos["ok"]) {
    // header("Location: .");
}
if (is_file("vista/" . $p . ".php")) {
    require_once("vista/" . $p . ".php");
} else {
    require_once("vista/404.php");
}