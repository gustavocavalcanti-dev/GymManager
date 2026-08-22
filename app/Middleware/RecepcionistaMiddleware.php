<?php

declare(strict_types=1);

class RecepcionistaMiddleware
{
    public function handle(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = $_SESSION['usuario'] ?? null;
        $perfisPermitidos = ['admin', 'recepcionista'];

        if ($usuario === null || !in_array($usuario['perfil'], $perfisPermitidos, true)) {
            $_SESSION['flash']['erro'] = 'Acesso negado. Apenas recepcionistas e administradores podem acessar esta área.';
            $basePath = defined('BASE_PATH') ? BASE_PATH : '';
            header('Location: ' . $basePath . '/');
            exit;
        }

        return true;
    }
}
