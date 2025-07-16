<?php

require_once __DIR__ . '/vendor/autoload.php'; // inclure l'autochargeur de Composer

//CONNECTION TO THE DATABASE
$host = $_SERVER['HTTP_HOST'];

if (in_array($host, ['localhost', '127.0.0.1', '::1'])) {
    // Local connection (XAMPP)
    $dbHost = 'localhost';
    $dbName = 'ecoride';
    $dbPort = 3306;
    $dbUser = 'admin_php';
    $dbPass = 'Test1234!';

    // MongoDB local connection
    $mongoUri = 'mongodb://localhost:27017';
    $mongoDbName = 'ecoride';
} else {
    // AlwaysData connection (production)
    $dbHost = 'mysql-ecoride-sge.alwaysdata.net';
    $dbName = 'ecoride-sge_ecoride';
    $dbPort = 3306;
    $dbUser = '411431';
    $dbPass = 'Germain14!';

    // MongoDB Atlas
    $mongoUri = 'mongodb+srv://tennis00015:KPlwAD4WolWKW4hh@ecoride-cluster.9cv0pxh.mongodb.net/?retryWrites=true&w=majority&appName=ecoride-cluster';
    $mongoDbName = 'ecoride';
}

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

try {
    $mongoClient = new MongoDB\Client($mongoUri);
    $mongoDb = $mongoClient->$mongoDbName;
} catch (Exception $e) {
    die("Erreur de connexion à MongoDB : " . $e->getMessage());
}
