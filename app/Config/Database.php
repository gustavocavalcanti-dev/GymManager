<?php

declare(strict_types=1);

return [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'port'     => getenv('DB_PORT') ?: '3306',
    'dbname'   => getenv('DB_DATABASE') ?: 'gym_manager',
    'user'     => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: 'masterkey',
    'charset'  => getenv('DB_CHARSET') ?: 'utf8mb4',
];
