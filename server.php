<?php

use App\HttpServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use Reaponse\Http\Middleware;

require 'vendor/autoload.php';

define('BASE_DIR', __DIR__ . '/');

$loop = Factory::create();
$uri = '0.0.0.0:9200';

$appHttpServer = new HttpServer();

$httpServer = new \React\Http\Server($loop, new Middleware($appHttpServer));
$socketServer = new Server($uri, $loop);

$httpServer->listen($socketServer);
$httpServer->on('error', function (Throwable $throwable){
    echo $throwable;
});

echo "Server started at http://{$uri}\n";
$loop->run();