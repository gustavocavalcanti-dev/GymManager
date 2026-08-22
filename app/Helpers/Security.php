<?php

declare(strict_types=1);

class Security
{
    public static function gerarTokenCSRF(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validarTokenCSRF(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            return false;
        }

        $valido = hash_equals($_SESSION['csrf_token'], $token);
        unset($_SESSION['csrf_token']);

        return $valido;
    }

    public static function campoCSRF(): string
    {
        $token = self::gerarTokenCSRF();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function hashSenha(string $senha): string
    {
        return password_hash($senha, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public static function verificarSenha(string $senha, string $hash): bool
    {
        return password_verify($senha, $hash);
    }

    public static function sanitizar(string $valor): string
    {
        return htmlspecialchars(trim($valor), ENT_QUOTES, 'UTF-8');
    }
}
