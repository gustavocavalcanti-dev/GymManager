<?php

declare(strict_types=1);

class AuthMiddleware
{
    public function handle(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = $_SESSION['usuario'] ?? null;

        if ($usuario === null) {
            $_SESSION['flash']['erro'] = 'Você precisa estar logado para acessar esta página.';
            $this->redirecionar('/login');
        }

        if (($usuario['status'] ?? 'inativo') !== 'ativo') {
            unset($_SESSION['usuario']);
            $_SESSION['flash']['erro'] = 'Sua conta está inativa. Entre em contato com o administrador.';
            $this->redirecionar('/login');
        }

        return true;
    }

    private function redirecionar(string $rota): never
    {
        $basePath = defined('BASE_PATH') ? BASE_PATH : '';
        header('Location: ' . $basePath . $rota);
        exit;
    }
}
