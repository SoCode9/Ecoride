<?php

// --- Chargement du .env (local uniquement) ---
$envFile = __DIR__ . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        if ($k !== '') putenv("$k=$v");
    }
}

// --- Autoload Composer ---
require_once __DIR__ . '/vendor/autoload.php';

// --- Connexions DB ---
require_once __DIR__ . '/database/MysqlConnection.php';
require_once __DIR__ . '/database/MongoConnection.php';
