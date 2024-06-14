<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}
require_once ("modelo/" . $p . ".php");
if (!empty($_POST)) {
    $o = new nombreclase(); // se pone el nombre de la clase del modelo
    if ($_POST["accion"] == "incluir") { 
        $response = $o->incluir_wada($_POST["id_atleta"], $_POST["estado"],$_POST["ultima_actualizacion"]); // incluir con sus parametros
        echo json_encode($response);
    } else if($_POST["accion"] == "listadowada"){
        $response = $o->listado_wada(); // listado
        echo json_encode($response);
    }
    exit;
}
if (is_file("vista/" . $p . ".php")) {
    require_once ("vista/" . $p . ".php");
} else {
    require_once ("comunes/404.php");
}