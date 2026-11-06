<?php

function conectar(?string $envPath = null): PDO
{
    $configPath = $envPath ?? __DIR__ . '/env.php';
    $config = require $configPath;
    $db = $config['db'] ?? [];

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $db['host'] ?? '127.0.0.1',
        (int) ($db['port'] ?? 3306),
        $db['dbname'] ?? 'bodega'
    );

    $pdo = new PDO(
        $dsn,
        $db['user'] ?? 'root',
        $db['password'] ?? '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}
