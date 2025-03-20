<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";
require_once "../class/Driver.php";

$idUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$driver = new Driver($pdo, $idUser);

$customPreferencesInDB = $driver->loadCustomPreferences($pdo, $idUser);

foreach ($customPreferencesInDB as $preference) {
    if ($preference == !null) {
        echo '<hr>';
        echo $preference;
    }
}