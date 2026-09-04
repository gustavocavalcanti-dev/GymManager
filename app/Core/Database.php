<?php

declare(strict_types=1);

class Database
{
    private static ?\PDO $conexao = null;

    public static function conectar(): \PDO
    {
        if (self::$conexao !== null) {
            return self::$conexao;
        }

        $config = require dirname(__DIR__) . '/Config/Database.php';

        $host     = trim((string) ($config['host'] ?? 'localhost'));
        $port     = trim((string) ($config['port'] ?? '3306'));
        $dbname   = trim((string) ($config['dbname'] ?? ''));
        $user     = trim((string) ($config['user'] ?? ''));
        $password = (string) ($config['password'] ?? '');
        $charset  = trim((string) ($config['charset'] ?? 'utf8mb4'));

        $opcoes = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$conexao = new \PDO(
                "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}",
                $user,
                $password,
                $opcoes
            );

            return self::$conexao;
        } catch (\PDOException $erroPrincipal) {
            /*
             * InfinityFree usa nomes de banco prefixados (ex.: if0_12345678_nome).
             * Se host/usuario/senha estiverem certos, mas o nome do banco no arquivo
             * estiver diferente do banco criado no painel, tentamos localizar de forma
             * segura o unico banco acessivel desta conta.
             */
            try {
                $servidor = new \PDO(
                    "mysql:host={$host};port={$port};charset={$charset}",
                    $user,
                    $password,
                    $opcoes
                );

                $bancos = $servidor->query('SHOW DATABASES')->fetchAll(\PDO::FETCH_COLUMN);
                $ignorados = ['information_schema', 'mysql', 'performance_schema', 'sys'];

                $candidatos = array_values(array_filter(
                    $bancos,
                    static fn ($banco): bool => !in_array((string) $banco, $ignorados, true)
                ));

                // Primeiro tenta um banco da propria conta que contenha "gym" no nome.
                $gym = array_values(array_filter(
                    $candidatos,
                    static fn ($banco): bool => stripos((string) $banco, 'gym') !== false
                ));

                $bancoSelecionado = null;
                if (count($gym) === 1) {
                    $bancoSelecionado = (string) $gym[0];
                } elseif (count($candidatos) === 1) {
                    $bancoSelecionado = (string) $candidatos[0];
                }

                if ($bancoSelecionado !== null) {
                    $bancoEscapado = str_replace('`', '``', $bancoSelecionado);
                    $servidor->exec("USE `{$bancoEscapado}`");
                    self::$conexao = $servidor;
                    return self::$conexao;
                }
            } catch (\Throwable $erroFallback) {
                self::registrarErro($erroPrincipal, $erroFallback);
                die('Erro de conexao com o banco de dados. Abra /diagnostico.php para identificar o motivo.');
            }

            self::registrarErro($erroPrincipal);
            die('Erro de conexao com o banco de dados. Abra /diagnostico.php para identificar o motivo.');
        }
    }

    private static function registrarErro(\Throwable $erroPrincipal, ?\Throwable $erroFallback = null): void
    {
        $pastaLogs = dirname(__DIR__, 2) . '/storage/logs';

        if (!is_dir($pastaLogs)) {
            @mkdir($pastaLogs, 0775, true);
        }

        $mensagem = '[' . date('Y-m-d H:i:s') . '] Banco: ' . $erroPrincipal->getMessage();
        if ($erroFallback !== null) {
            $mensagem .= ' | Fallback: ' . $erroFallback->getMessage();
        }
        $mensagem .= PHP_EOL;

        @file_put_contents($pastaLogs . '/database.log', $mensagem, FILE_APPEND | LOCK_EX);
        error_log(trim($mensagem));
    }
}
