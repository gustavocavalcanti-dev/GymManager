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
        $permitidos = ['administrador', 'atendente'];

        if ($usuario === null || !in_array((string)($usuario['perfil'] ?? ''), $permitidos, true)) {
            $_SESSION['flash']['erro'] = 'Acesso negado. Apenas recepcionistas e administradores podem realizar esta operação.';
            $basePath = defined('BASE_PATH') ? BASE_PATH : '';
            header('Location: ' . $basePath . '/');
            exit;
        }

        return true;
    }
}
