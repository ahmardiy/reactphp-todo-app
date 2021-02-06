<?php


namespace App\Controllers;


use Reaponse\Http\ResponseInterface;

class MainController
{
    public function index(ResponseInterface $response): void
    {
        $response->end('Hello World');
    }
}