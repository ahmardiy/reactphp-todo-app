<?php


namespace App;


use QuickRoute\Route\Collector;
use QuickRoute\Route\Dispatcher;
use Reaponse\Http\ResponseInterface;

class HttpServer implements \Reaponse\Http\HandlerInterface
{
    private Collector $collector;
    private Dispatcher $dispatcher;


    public function __construct()
    {
        $this->collector = Collector::create()
            ->collectFile(BASE_DIR . 'routes.php')
            ->register();

        $this->dispatcher = Dispatcher::create($this->collector);
    }

    /**
     * @inheritDoc
     */
    public function handle(ResponseInterface $response): void
    {
        $this->handleRouting($response);
    }

    public function handleRouting(ResponseInterface $response): void
    {
        $dispatchResult = $this->dispatcher->dispatch(
            $response->request()->getMethod(),
            $response->request()->getUri()->getPath()
        );

        switch (true) {
            case $dispatchResult->isFound():
                $route = $dispatchResult->getRoute();
                $controller = $route->getHandler()[0];
                $method = $route->getHandler()[1];

                call_user_func([new $controller(), $method], $response);
                break;
            case $dispatchResult->isNotFound():
                $response->end('NOT FOUND :)');
                break;
            case $dispatchResult->isMethodNotAllowed():
                $response->end('METHOD NOT ALLOWED');
                break;
        }
    }
}