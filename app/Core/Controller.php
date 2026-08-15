<?php

declare(strict_types=1);

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException(
                "A view {$view} não foi encontrada."
            );
        }

        if (!isset($data['sucesso'])) {
            $data['sucesso'] = $this->getFlash('sucesso');
        }
        if (!isset($data['erro'])) {
            $data['erro'] = $this->getFlash('erro');
        }
        if (!isset($data['erros'])) {
            $data['erros'] = $this->getFlash('erros') ?: [];
        }

        extract($data);

        require $viewPath;
    }

    protected function setFlash(string $chave, mixed $valor): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$chave] = $valor;
    }

    protected function getFlash(string $chave): mixed
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['flash'][$chave])) {
            $valor = $_SESSION['flash'][$chave];
            unset($_SESSION['flash'][$chave]);
            return $valor;
        }
        return null;
    }

    protected function redirect(string $caminho): void
    {
        $basePath = defined('BASE_PATH') ? BASE_PATH : '';
        $url = $basePath . '/' . ltrim($caminho, '/');
        header('Location: ' . $url);
        exit;
    }
}
