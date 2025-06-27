<?php
require_once __DIR__ . "/vendor/autoload.php";

use Gymsys\Core\Database;
use Gymsys\Model\Notificaciones;

try {
    $database = new Database();
    $resultado = Notificaciones::crearNotificaciones($database);
} catch (\Exception $e) {
    $resultado = [
        "ok" => false,
        "mensaje" => $e->getMessage()
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($resultado);
$database->desconecta();
