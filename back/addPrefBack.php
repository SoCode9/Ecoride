<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";
require_once "../class/Driver.php";

$driverId = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === "formPref") {
    header('Content-Type: application/json');
    try {
        $newPrefInsert = $_POST['new_pref'];
        $driver = new Driver($pdo, $driverId);
        $customPreferencesInDB = $driver->loadCustomPreferences($pdo, $driverId);
        // Counts the number of non-null preferences
        $nonEmptyPrefsCount = 0;
        foreach ($customPreferencesInDB as $pref) {
            if (!is_null($pref)) {
                $nonEmptyPrefsCount++;
            }
        }

        if ($nonEmptyPrefsCount < 3) {
            $driver->addCustomPreference($pdo, $driverId, $newPrefInsert);
            echo json_encode([
                "success" => true,
                "newPrefInsert" => $newPrefInsert
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => "Impossible d'ajouter plus de préférences (maximum : 3)."
            ]);
        }

    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}