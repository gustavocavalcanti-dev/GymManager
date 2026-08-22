<?php

declare(strict_types=1);

class AuthMiddleware
{
    public function handle(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['usuario'])) {
            $_SESSION['flash']['erro'] = 'Você precisa estar logado para acessar esta página.';
            $basePath = defined('BASE_PATH') ? BASE_PATH : '';
            header('Location: ' . $basePath . '/login');
            exit;
        }

        if (empty($_SESSION['usuario']['ativo'])) {
            unset($_SESSION['usuario']);
            $_SESSION['flash']['erro'] = 'Sua conta foi desativada. Entre em contato com o administrador.';
            $basePath = defined('BASE_PATH') ? BASE_PATH : '';
            header('Location: ' . $basePath . '/login');
            exit;
        }

        return true;
    }
}
