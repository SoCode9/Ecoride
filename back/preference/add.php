<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
requireDriver(); // Checks whether the user is a driver
require_once __DIR__ . "/../../init.php";
require_once __DIR__ . "/../../class/Driver.php";

$pdo = MysqlConnection::getPdo();

$driverId = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === "formPref") {
    header('Content-Type: application/json');
    try {
        $newPrefInsert = $_POST['new_pref'];
        $driver = new Driver($pdo, $driverId);

        $driver->addCustomPreference($newPrefInsert);
        echo json_encode([
            "success" => true,
            "newPrefInsert" => $newPrefInsert
        ]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
