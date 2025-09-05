<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

// --- Mini loader .env (local seulement) ---
$envFile = __DIR__ . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        if ($k !== '') putenv("$k=$v");
    }
}
function env(string $key, ?string $default = null): ?string
{
    $v = getenv($key);
    return $v === false ? $default : $v;
}

function pdo(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    try {
        $host    = env('DB_HOST', '127.0.0.1');
        $port    = (int) env('DB_PORT', '3306');
        $name    = env('DB_NAME', 'ecoride');
        $user    = env('DB_USER', 'root');
        $pass    = env('DB_PASS', '');
        $charset = env('DB_CHARSET', 'utf8mb4');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $name, $charset);

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (Exception $e) {
        error_log("PDO error : " . $e->getMessage());
        header('Location: ' . BASE_URL . '/index.php');
        $_SESSION['error_message'] = "Une erreur est survenue";
        exit;
    }
}

function mongo_db(): ?MongoDB\Database
{
    static $db = null;
    if ($db instanceof MongoDB\Database) return $db;

    $uri = env('MONGO_URI');
    $name = env('MONGO_DB');

    if (!$uri || !$name) return null;

    $client = new MongoDB\Client($uri);
    $db = $client->selectDatabase($name);
    return $db;
}

$GLOBALS['mongoDb'] = mongo_db();
