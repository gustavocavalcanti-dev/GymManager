<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run(): void
    {
        $this->router->dispatch();
    }
}