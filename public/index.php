<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Router;

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDirectory = dirname(__DIR__) . '/app/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));

    $file = $baseDirectory
        . str_replace('\\', '/', $relativeClass)
        . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$router = new Router();

$routesFile = dirname(__DIR__) . '/app/Routes/web.php';

if (file_exists($routesFile)) {
    require $routesFile;
}

$app = new App($router);

$app->run();