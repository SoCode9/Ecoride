<?php

use MongoDB\Client as MongoClient;
use MongoDB\Database as MongoDatabase;

class MongoConnection
{
    private static ?MongoConnection $instance = null;
    private ?MongoDatabase $mongoDb = null;

    private function __construct()
    {
        $uri  = getenv('MONGO_URI');
        $name = getenv('MONGO_DB');

        if ($uri && $name) {
            $client = new MongoClient($uri);
            $this->mongoDb = $client->selectDatabase($name);
        }
    }

    public static function getMongoDb(): ?MongoDatabase
    {
        if (!self::$instance) {
            self::$instance = new MongoConnection();
        }
        return self::$instance->mongoDb;
    }
}
