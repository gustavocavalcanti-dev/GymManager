<?php

declare(strict_types=1);

class Router
{
    private array $routes = [];

    public function get(
        string $uri,
        string $controller,
        string $method
    ): void {
        $this->routes['GET'][$uri] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function post(
        string $uri,
        string $controller,
        string $method
    ): void {
        $this->routes['POST'][$uri] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $requestUri = parse_url(
            $_SERVER['REQUEST_URI'] ?? '/', 
            PHP_URL_PATH
        ) ?? '/';

        $basePath = defined('BASE_PATH')
            ? BASE_PATH
            : str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

        if ($basePath === '/' || $basePath === '.') {
            $basePath = '';
        }

        if ($basePath !== '' && stripos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        $requestUri = '/' . trim($requestUri, '/');
        if ($requestUri === '//') {
            $requestUri = '/';
        }

        $route = $this->routes[$requestMethod][$requestUri] ?? null;

        if ($route === null) {
            http_response_code(404);

            echo '<h1>404</h1>';
            echo '<p>Página não encontrada.</p>';

            return;
        }

        $controllerClass = $route['controller'];
        $controllerMethod = $route['method'];

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException(
                "Controller não encontrado: {$controllerClass}"
            );
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $controllerMethod)) {
            throw new \RuntimeException(
                "Método não encontrado: {$controllerMethod}"
            );
        }

        $controller->$controllerMethod();
    }
}
