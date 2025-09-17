<?php

class MysqlConnection
{
    private static ?MysqlConnection $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $host    = getenv('DB_HOST') ?: '127.0.0.1';
        $port    = (int)(getenv('DB_PORT') ?: '3306');
        $name    = getenv('DB_NAME') ?: 'ecoride';
        $user    = getenv('DB_USER') ?: 'root';
        $pass    = getenv('DB_PASS') ?: '';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $name, $charset);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public static function getPdo(): PDO
    {
        if (!self::$instance) {
            self::$instance = new MysqlConnection();
        }
        return self::$instance->pdo;
    }
}
