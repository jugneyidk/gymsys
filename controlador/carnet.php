<?php
if (!is_file("modelo/" . $p . ".php")) {
    echo "No existe el modelo.";
    exit;
}
if (!empty($_GET["id"])) {
    include 'lib/phpqrcode-master/qrlib.php';
    $o = new Carnet();
    $cedula = $_GET['id'];
    $carnet = $o->obtener_carnet($cedula);
    if (!isset($carnet)) {
        require_once "vista/404.php";
        exit;
    }
    // Guardar o mostrar el carné
    header("Content-Disposition: Inline; filename=$cedula.png");
    header("Content-Type: image/png");
    imagepng($carnet["imagen"]); // Enviar al navegador
    // Liberar memoria
    imagedestroy($carnet["imagen"]);
    exit;
} else {
    header("location: .");
}