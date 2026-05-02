<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'database' => getenv('DB_DATABASE') ?: 'blog',
        'username' => getenv('DB_USERNAME') ?: 'blog',
        'password' => getenv('DB_PASSWORD') ?: 'secret',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_path' => dirname(__DIR__),
    ],
];
