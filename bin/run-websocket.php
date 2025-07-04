<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Gymsys\Websocket\NotificationServer;

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/config.php';
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new NotificationServer()
        )
    ),
    8080 // Puerto del servidor WebSocket
);

$server->run();
