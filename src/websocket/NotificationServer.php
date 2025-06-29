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
     * Constructor de NotificationServer.
     * Inicializa el almacenamiento de clientes y la conexión a la base de datos.
     */
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
                $this->cleanupStaleCache(); // Trigger cleanup after authentication
            } catch (\Throwable $e) {
                echo "Error de autenticación para la conexión {$from->resourceId}: " . $e->getMessage() . "\n";
                $from->send(json_encode(['error' => 'Authentication failed']));
                $from->close();
            }
        } else if (isset($data['action']) && $data['action'] === 'fetch_notifications' && isset($from->userId)) {
            // Si el cliente ya está autenticado y solicita notificaciones
            $this->sendNotificationsToUser($from);
            $this->cleanupStaleCache(); // Trigger cleanup after fetching notifications
        } else if (isset($data['action']) && $data['action'] === 'ping' && isset($from->userId)) {
            // Handle ping action: check for cached notifications and send them
            $userId = $from->userId; // Assign userId from the connection to a local variable
            // Define the cache directory for notifications
            $cacheDir = dirname(__DIR__, 2) . '/cache/notificaciones/';
            // Define the pattern to match cached notification files for the current user
            $pattern = $cacheDir . 'notif_' . $userId . '_*.json';
            // Find all files matching the pattern
            $files = glob($pattern);

            $notifications = [];
            // Iterate through each found file
            foreach ($files as $file) {
                // Decode the JSON content of the file
                $data = json_decode(file_get_contents($file), true);
                // Merge the notifications from the file into the main notifications array
                $notifications = array_merge($notifications, $data['notifications']);
                // Delete the cache file after reading
                unlink($file);
            }

            // Send a pong response to acknowledge the ping
            $from->send(json_encode(['action' => 'pong']));
            // If there are cached notifications, send them to the client
            if (!empty($notifications)) {
                $from->send(json_encode([
                    "type" => "new_notification",
                    "data" => [
                        "notificaciones" => $notifications
                    ]
                ]));
            }
            $this->cleanupStaleCache(); // Trigger cleanup after ping
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
        $this->cleanupStaleCache(); // Trigger cleanup when a connection closes
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

    /**
     * Limpia archivos de caché obsoletos de usuarios desconectados.
     * Esta función se llama periódicamente para eliminar archivos de caché de notificaciones
     * que pertenecen a usuarios que ya no están conectados activamente al servidor WebSocket.
     */
    protected function cleanupStaleCache()
    {
        // Define la ruta al directorio de caché para notificaciones
        $cacheDir = __DIR__ . '/../../cache/notificaciones/';

        // Obtener todos los archivos que coinciden con el patrón 'notif_*.json' en el directorio de caché
        $files = glob($cacheDir . 'notif_*.json');

        // Inicializar un array para almacenar los IDs de usuario de conexiones activas
        $activeUserIds = [];
        // Iterar a través de todos los clientes conectados para recolectar sus IDs de usuario
        foreach ($this->clients as $client) {
            // Asegurarse de que la propiedad userId existe para el cliente antes de agregarla
            if (isset($client->userId)) {
                $activeUserIds[$client->userId] = true; // Usar array asociativo para búsquedas más rápidas
            }
        }

        // Registrar el número de usuarios activos para depuración
        echo "Ejecutando limpieza de caché. Usuarios activos: " . json_encode(array_keys($activeUserIds)) . "\n";

        // Iterar a través de cada archivo de caché encontrado
        foreach ($files as $file) {
            // Extraer el nombre del archivo de la ruta completa
            $filename = basename($file);
            // Usar una expresión regular para extraer el ID de usuario del nombre del archivo
            // El patrón 'notif_(\d+)_' busca 'notif_', seguido de uno o más dígitos (capturados), y luego '_'.
            if (preg_match('/notif_(\d+)_/', $filename, $matches)) {
                // El ID de usuario capturado está en $matches[1]
                $userId = $matches[1];
                echo "Procesando archivo de caché: {$filename}. ID de usuario extraído: {$userId}. ";
                // Verificar si el ID de usuario extraído NO está en la lista de IDs de usuario activos
                // Usando array_key_exists para una búsqueda más rápida con array asociativo
                if (!array_key_exists($userId, $activeUserIds)) {
                    // If the user is not active, delete their cache file
                    try {
                        unlink($file);
                        echo "Deleted stale cache file: {$filename} for user {$userId}\n";
                    } catch (\Throwable $e) {
                        echo "Error deleting file {$filename} for user {$userId}: {$e->getMessage()}\n";
                    }
                } else {
                    echo "User {$userId} is active, keeping file.\n";
                }
            } else {
                echo "Filename {$filename} does not match expected pattern.\n";
            }
        }
    }
}
