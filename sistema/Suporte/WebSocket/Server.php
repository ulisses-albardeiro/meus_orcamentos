<?php

namespace sistema\Suporte\WebSocket;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use sistema\Suporte\WebSocket\Chat;

require __DIR__ . '/../../../vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

$server->run();
