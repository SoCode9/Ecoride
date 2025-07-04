<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../../database.php";
require_once __DIR__ . "/../../../class/Driver.php";
require_once __DIR__ . "/../../../back/user/user_space.php";

$idUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$driver = new Driver($pdo, $idUser);

if (isset($driver)) {
    $customPreferencesInDB = $driver->loadCustomPreferences();
    if (!empty($customPreferencesInDB)) {
        foreach ($customPreferencesInDB as $preference) {
            if ($preference !== null) {
                echo '<hr>';
                $url = BASE_URL . "/back/preference/delete.php?action=delete_pref&id=" . urlencode($preference);

                echo "<span style='display:flex; gap:4px; align-items:center;'>
        <a class='hidden delete-pref-icon' href='$url'> <img src='" . BASE_URL . "/icons/Supprimer.png' class='img-width-20' style='cursor: pointer;'></a>
        $preference
    </span>";
            }
        }
    }
}

