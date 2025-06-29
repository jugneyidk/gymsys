<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ ."/../config/config.php";
use Gymsys\Core\Database;
use Gymsys\Model\Notificaciones;

// Definir el directorio de caché
$cacheDir = __DIR__ . '/../cache/notificaciones/';

try {
    $database = new Database();
    $resultado = Notificaciones::crearNotificaciones($database);

    // Verificar si se crearon notificaciones y si existen notificaciones agrupadas
    if ($resultado['ok'] && !empty($resultado['grouped_notifications'])) {
        // Asegurarse de que el directorio de caché existe
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true); // Crear directorio recursivamente con todos los permisos
        }

        // Iterar a través de las notificaciones agrupadas y guardarlas en archivos de caché
        foreach ($resultado['grouped_notifications'] as $userId => $notifications) {
            $timestamp = (new DateTime())->format('YmdHis'); // Marca de tiempo actual para nombre de archivo único
            $filename = $cacheDir . "notif_{$userId}_{$timestamp}.json";

            $cacheContent = [
                "user_id" => $userId,
                "notifications" => $notifications
            ];

            // Guardar el contenido JSON en el archivo
            file_put_contents($filename, json_encode($cacheContent, JSON_PRETTY_PRINT));
        }
    }

    // Notificación de prueba estática
    $testUserId = "28609560";
    $testNotification = [
        "id" => uniqid(), // Generar un ID único para la notificación de prueba
        "titulo" => "Notificación de Prueba",
        "mensaje" => "Este es un mensaje de notificación de prueba para el usuario {$testUserId}.",
        "leida" => 0,
        "objetivo" => "/dashboard",
        "fecha_creacion" => (new DateTime())->format('Y-m-d H:i:s')
    ];

    $testCacheContent = [
        "user_id" => $testUserId,
        "notifications" => [$testNotification]
    ];

    $testTimestamp = (new DateTime())->format('YmdHis');
    $testFilename = $cacheDir . "notif_{$testUserId}_{$testTimestamp}.json";
    file_put_contents($testFilename, json_encode($testCacheContent, JSON_PRETTY_PRINT));
    echo "Notificación de prueba creada para el usuario {$testUserId} en {$testFilename}\n";

} catch (\Exception $e) {
    $resultado = [
        "ok" => false,
        "mensaje" => $e->getMessage()
    ];
    error_log("Error en notificacionesCronJob: " . $e->getMessage());
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($resultado);
$database->desconecta();
