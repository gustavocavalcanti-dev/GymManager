<?php

declare(strict_types=1);

namespace App\Core;

final class Database
{
    private static ?\PDO $conexao = null;

    public static function conectar(): \PDO
    {
        if (self::$conexao instanceof \PDO) {
            return self::$conexao;
        }

        $config = require dirname(__DIR__) . '/Config/Database.php';

        $host = trim((string)($config['host'] ?? 'localhost'));
        $port = trim((string)($config['port'] ?? '3306'));
        $dbname = trim((string)($config['dbname'] ?? 'gym_manager'));
        $user = trim((string)($config['user'] ?? 'root'));
        $password = (string)($config['password'] ?? '');
        $charset = trim((string)($config['charset'] ?? 'utf8mb4'));

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

        self::$conexao = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$conexao;
    }

    private function __construct()
    {
    }
}
