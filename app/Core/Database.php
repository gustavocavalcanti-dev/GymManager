<?php

declare(strict_types=1);

class Database
{
    private static ?\PDO $conexao = null;

    public static function conectar(): \PDO
    {
        if (self::$conexao === null) {
            try {
                self::$conexao = new \PDO(
                    'mysql:host=localhost;port=3306;dbname=gym_manager;charset=utf8mb4',
                    'root',
                    'masterkey'
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
                die('Erro na conexão: ' . $erro->getMessage());
            }
        }

        return self::$conexao;
    }
}