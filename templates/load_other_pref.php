<?php

require_once "../database.php";
$idUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$driver = new Driver($pdo, $idUser);

$customPreferences = $driver->loadCustomPreferences($pdo, $idUser);

foreach ($customPreferences as $preference) {
    if ($preference == !null) {
        echo '<hr>';
        echo $preference;
    }
}