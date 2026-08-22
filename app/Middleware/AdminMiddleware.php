<?php

declare(strict_types=1);

class AdminMiddleware
{
    public function handle(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = $_SESSION['usuario'] ?? null;

        if ($usuario === null || $usuario['perfil'] !== 'admin') {
            $_SESSION['flash']['erro'] = 'Acesso negado. Você não tem permissão para acessar esta área.';
            $basePath = defined('BASE_PATH') ? BASE_PATH : '';
            header('Location: ' . $basePath . '/');
            exit;
        }

        return true;
    }
}
