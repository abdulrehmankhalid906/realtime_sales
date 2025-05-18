<?php

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

use App\WebSocket\WebSocketServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;


    require __DIR__ . '/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new WebSocketServer()
            )
        ),
        8080
    );

    $server->run();