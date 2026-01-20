<?php

return [
    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'fashion',
        'user' => getenv('DB_USER') ?: 'fashion_app',
        'pass' => getenv('DB_PASS') ?: '2552',
        'charset' => 'utf8mb4'
    ]
];