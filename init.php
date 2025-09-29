<?php

// 1) Session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 2) Chargement du .env (local uniquement)
$envFile = __DIR__ . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        if ($k !== '') putenv("$k=$v");
    }
}

// 3) BASE_URL
if (!defined('BASE_URL')) {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (file_exists('/.dockerenv')) {
        // Conteneur Docker (localhost:8080) -> racine
        define('BASE_URL', '');
    } elseif (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
        // XAMPP local (projet sous /EcoRide)
        define('BASE_URL', '/EcoRide');
    } else {
        // Prod (domaine)
        define('BASE_URL', '');
    }
}

// 4)  Autoload Composer 
require_once __DIR__ . '/vendor/autoload.php';

// 5) Connexions DB 
require_once __DIR__ . '/database/MysqlConnection.php';
require_once __DIR__ . '/database/MongoConnection.php';
