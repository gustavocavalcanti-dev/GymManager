<?php

declare(strict_types=1);

class ProfessorMiddleware
{
    public function handle(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = $_SESSION['usuario'] ?? null;
        $perfisPermitidos = ['admin', 'professor'];

        if ($usuario === null || !in_array($usuario['perfil'], $perfisPermitidos, true)) {
            $_SESSION['flash']['erro'] = 'Acesso negado. Apenas professores e administradores podem acessar esta área.';
            $basePath = defined('BASE_PATH') ? BASE_PATH : '';
            header('Location: ' . $basePath . '/');
            exit;
        }

        return true;
    }
}
