<?php

namespace App\Core;

class App
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run()
    {
        Session::start();
        $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }
}
