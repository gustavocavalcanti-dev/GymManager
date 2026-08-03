<?php

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $baseDirectory = dirname(__DIR__) . '/app/';
    $paths = [
        $baseDirectory . 'Core/',
        $baseDirectory . 'Controllers/',
        $baseDirectory . 'Models/',
        $baseDirectory . 'Config/',
        $baseDirectory . 'Helpers/',
        $baseDirectory . 'Middleware/',
    ];

    $file = $class . '.php';

    foreach ($paths as $path) {
        $candidate = $path . $file;
        if (file_exists($candidate)) {
            require_once $candidate;
            return;
        }
    }

    $candidate = $baseDirectory . str_replace('\\', '/', $class) . '.php';
    if (file_exists($candidate)) {
        require_once $candidate;
    }
});

define('BASE_PATH', str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])));

$router = new Router();

$routesFile = dirname(__DIR__) . '/app/Routes/web.php';

if (file_exists($routesFile)) {
    require $routesFile;
}

$app = new App($router);

$app->run();