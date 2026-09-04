<?php

declare(strict_types=1);

class Database
{
    private static ?\PDO $conexao = null;

    public static function conectar(): \PDO
    {
        if (self::$conexao === null) {
            // Lê configurações do arquivo Config/Database.php
            $config = require dirname(__DIR__) . '/Config/Database.php';

            $host     = $config['host']     ?? 'localhost';
            $port     = $config['port']     ?? '3306';
            $dbname   = $config['dbname']   ?? 'gym_manager';
            $user     = $config['user']     ?? 'root';
            $password = $config['password'] ?? '';
            $charset  = $config['charset']  ?? 'utf8mb4';

            try {
                self::$conexao = new \PDO(
                    "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}",
                    $user,
                    $password
                );

                self::$conexao->setAttribute(
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                );

                self::$conexao->setAttribute(
                    \PDO::ATTR_DEFAULT_FETCH_MODE,
                    \PDO::FETCH_ASSOC
                );
            } catch (\PDOException $erro) {
                // Em produção, nunca exibir detalhes do erro ao usuário
                error_log('Erro na conexão com banco de dados: ' . $erro->getMessage());
                die('Erro interno no servidor. Contate o administrador.');
            }
        }

        return self::$conexao;
    }
}