<?php

namespace Gymsys\Websocket;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Gymsys\Core\Database;
use Gymsys\Model\Notificaciones;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\JWTHelper;
class NotificationServer implements MessageComponentInterface
{
    protected $clients;
    private $database;

    /**
     * Constructor del websocket de notificaciones.
     * Inicializa el almacenamiento de clientes y la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->database = new Database();
        echo "Servidor de notificaciones iniciado.\n";
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        echo "Nueva conexión! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $numRecv = count($this->clients);
        echo sprintf('Conexión %d enviando mensaje "%s" a %d conexion%s' . "\n", $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 'es');
        $data = json_decode($msg, true);

        if (isset($data['action']) && $data['action'] === 'authenticate' && isset($data['token'])) {
            $token = str_replace('Bearer ', '', $data['token']);
            try {
                $decoded = JWTHelper::decodificarToken($token);
                $userId = $decoded->sub;
                $userId = Cipher::aesDecrypt($userId);
                $from->userId = $userId;
                echo "Conexión {$from->resourceId} autenticada como usuario {$userId}\n";
                $this->enviarNotificaciones($from);
                $this->limpiarNotificacionesNuevas();
            } catch (\Throwable $e) {
                echo "Error de autenticación para la conexión {$from->resourceId}: " . $e->getMessage() . "\n";
                $from->send(json_encode(['error' => 'Authentication failed']));
                $from->close();
            }
        } else if (isset($data['action']) && $data['action'] === 'fetch_notifications' && isset($from->userId)) {
            // Si el cliente ya está autenticado y solicita notificaciones
            $this->enviarNotificaciones($from);
            $this->limpiarNotificacionesNuevas();
        } else if (isset($data['action']) && $data['action'] === 'ping' && isset($from->userId)) {
            $userId = $from->userId;
            $cacheDir = dirname(__DIR__, 2) . '/cache/notificaciones/';
            $pattern = $cacheDir . 'notif_' . $userId . '_*.json';
            $files = glob($pattern);
            $notifications = [];
            foreach ($files as $file) {
                $data = json_decode(file_get_contents($file), true);
                $notifications = array_merge($notifications, $data['notifications']);
                unlink($file);
            }
            $from->send(json_encode(['action' => 'pong']));
            if (!empty($notifications)) {
                $from->send(json_encode([
                    "type" => "new_notification",
                    "data" => [
                        "notificaciones" => $notifications
                    ]
                ]));
            }
            $this->limpiarNotificacionesNuevas();
        } else {
            // Mensaje no reconocido o no autenticado
            $from->send(json_encode(['error' => 'Mensaje no reconocido o no autenticado', 'data' => $from]));
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        echo "Conexión {$conn->resourceId} ha desconectado\n";
        $this->limpiarNotificacionesNuevas();
    }

    public function onError(ConnectionInterface $conn, \Throwable $e): void
    {
        echo "Ha ocurrido un error en la conexión {$conn->resourceId}: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Envia notificaciones a un usuario específico.
     * @param ConnectionInterface $conn La conexión del usuario.
     */
    private function enviarNotificaciones(ConnectionInterface $conn): void
    {
        if (!isset($conn->userId)) {
            return;
        }
        try {
            $notificacionesModel = new Notificaciones($this->database);
            $notificaciones = $notificacionesModel->obtenerNotificaciones($conn->userId);
            $conn->send(json_encode(['type' => 'notifications', 'data' => $notificaciones]));
        } catch (\Throwable $e) {
            echo "Error al obtener notificaciones para el usuario {$conn->userId}: {$e->getMessage()}\n";
            $conn->send(json_encode(['error' => 'Error al obtener notificaciones']));
        }
    }
    protected function limpiarNotificacionesNuevas(): void
    {
        // Ruta del cache de notificaciones
        $cacheDir = __DIR__ . '/../../cache/notificaciones/';
        $files = glob($cacheDir . 'notif_*.json');
        $activeUserIds = [];
        foreach ($this->clients as $client) {
            if (isset($client->userId)) {
                $activeUserIds[$client->userId] = true;
            }
        }
        echo "Ejecutando limpieza de caché. Usuarios activos: " . json_encode(array_keys($activeUserIds)) . "\n";
        foreach ($files as $file) {
            $filename = basename($file);
            if (preg_match('/notif_(\d+)_/', $filename, $matches)) {
                // El ID de usuario capturado está en $matches[1]
                $userId = $matches[1];
                echo "Procesando archivo de caché: {$filename}. ID de usuario extraído: {$userId}. ";
                if (!array_key_exists($userId, $activeUserIds)) {
                    try {
                        unlink($file);
                        echo "Se eliminó el archivo de caché obsoleto: {$filename} para el usuario {$userId}\n";
                    } catch (\Throwable $e) {
                        echo "Error al eliminar el archivo {$filename} para el usuario {$userId}: {$e->getMessage()}\n";
                    }
                } else {
                    echo "El usuario {$userId} está activo, manteniendo el archivo.\n";
                }
            } else {
                echo "El archivo {$filename} no coincide con el patrón esperado.\n";
            }
        }   
    }
}
