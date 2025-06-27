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

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->database = new Database();
        echo "Servidor de notificaciones iniciado.\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Almacenar la nueva conexión
        $this->clients->attach($conn);
        echo "Nueva conexión! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
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
                $from->userId = $userId; // Asocia el ID de usuario a la conexión
                echo "Conexión {$from->resourceId} autenticada como usuario {$userId}\n";
                $this->sendNotificationsToUser($from); // Enviar notificaciones al autenticarse
            } catch (\Throwable $e) {
                echo "Error de autenticación para la conexión {$from->resourceId}: " . $e->getMessage() . "\n";
                $from->send(json_encode(['error' => 'Authentication failed']));
                $from->close();
            }
        } else if (isset($data['action']) && $data['action'] === 'fetch_notifications' && isset($from->userId)) {
            // Si el cliente ya está autenticado y solicita notificaciones
            $this->sendNotificationsToUser($from);
        } else if (isset($data['action']) && $data['action'] === 'ping' && isset($from->userId)) {
            // Si el cliente ya está autenticado y solicita notificaciones
            $from->send(json_encode(['action' => 'pong']));
            $from->send(json_encode([
                "type" => "new_notification",
                "data" => [
                    "notificaciones" =>
                    [
                        [
                            "id" => 100,
                            "titulo" => "Mi bro mi bro",
                            "mensaje" => "Tu eres mi bro o o o",
                            "leida" => 0,
                            "objetivo" => "/dashboard",
                            "fecha_creacion" => "2024-11-29 06:40:36"
                        ]
                    ]
                ]
            ]));
        } else {
            // Mensaje no reconocido o no autenticado
            $from->send(json_encode(['error' => 'Mensaje no reconocido o no autenticado', 'data' => $from]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Desasociar la conexión cuando se cierra
        $this->clients->detach($conn);
        echo "Conexión {$conn->resourceId} ha desconectado\n";
    }

    public function onError(ConnectionInterface $conn, \Throwable $e)
    {
        echo "Ha ocurrido un error en la conexión {$conn->resourceId}: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Envía notificaciones a un usuario específico.
     * @param ConnectionInterface $conn La conexión del usuario.
     */
    private function sendNotificationsToUser(ConnectionInterface $conn)
    {
        if (!isset($conn->userId)) {
            return; // No se puede enviar notificaciones si el usuario no está autenticado
        }

        try {
            $notificacionesModel = new Notificaciones($this->database);
            $notificaciones = $notificacionesModel->obtenerNotificaciones($conn->userId);
            $conn->send(json_encode(['type' => 'notifications', 'data' => $notificaciones]));
        } catch (\Throwable $e) {
            echo "Error al obtener notificaciones para el usuario {$conn->userId}: {$e->getMessage()}\n";
            $conn->send(json_encode(['error' => 'Failed to fetch notifications']));
        }
    }

    /**
     * Envía una notificación a todos los clientes conectados que pertenecen a un usuario específico.
     * Esto es útil para notificaciones push desde el servidor (ej. nueva mensualidad, WADA vencida).
     * @param string $userId El ID del usuario al que se enviará la notificación.
     * @param array $notificationData Los datos de la notificación.
     */
    public function sendNotificationToUser(string $userId, array $notificationData)
    {
        foreach ($this->clients as $client) {
            if (isset($client->userId) && $client->userId === $userId) {
                $client->send(json_encode(['type' => 'new_notification', 'data' => $notificationData]));
            }
        }
    }

    /**
     * Envía una notificación a todos los clientes conectados.
     * @param array $notificationData Los datos de la notificación.
     */
    public function broadcastNotification(array $notificationData)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode(['type' => 'broadcast_notification', 'data' => $notificationData]));
        }
    }
}
