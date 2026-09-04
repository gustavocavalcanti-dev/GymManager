<?php

declare(strict_types=1);

/**
 * Configuração de conexão com o banco de dados.
 *
 * Em produção, substitua os valores abaixo pelas
 * variáveis de ambiente ou pelo arquivo .env.
 *
 * NUNCA versione credenciais reais no Git.
 */
return [
    'host'     => getenv('DB_HOST')     ?: 'localhost',
    'port'     => getenv('DB_PORT')     ?: '3306',
    'dbname'   => getenv('DB_DATABASE') ?: 'gym_manager',
    'user'     => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset'  => 'utf8mb4',
];
