<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function (string $class): void {
    $baseDirectory = dirname(__DIR__) . '/app/';
    $namespacedClass = str_replace('\\', '/', $class);

    if (str_starts_with($namespacedClass, 'App/')) {
        $candidate = $baseDirectory . substr($namespacedClass, 4) . '.php';
        if (file_exists($candidate)) {
            require_once $candidate;
            return;
        }
    }

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

    $candidate = $baseDirectory . $namespacedClass . '.php';
    if (file_exists($candidate)) {
        require_once $candidate;
    }
});

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
define('BASE_PATH', ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/'));

$router = new Router();

$routesFile = dirname(__DIR__) . '/app/Routes/web.php';

if (file_exists($routesFile)) {
    require $routesFile;
}

$app = new App($router);

$app->run();