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
        echo "<span style='display:flex; gap:4px; align-items:center;'><a href='../back/delete_pref.php?action=delete_pref&id=" . urlencode($preference) . "'><img src='../icons/Supprimer.png' class='imgFilter'
        style='cursor: pointer;'></a>$preference </span>";
    }
}