<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../database.php";
require_once "../class/Driver.php";

$prefToDelete = $_GET['id'];
$driverId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'delete_pref') {
    $driver = new Driver($pdo, $driverId);
    $customPreferencesInDB = $driver->loadCustomPreferences($pdo, $driverId);

    foreach ($customPreferencesInDB as $columnName => $value) {
        if ($value === $prefToDelete) {
            $sql = "UPDATE driver SET $columnName = NULL WHERE user_id = :driverId";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':driverId', $driverId, PDO::PARAM_INT);
            $statement->execute();
            header('Location:../index/userSpaceIndex.php');
            exit;

        }
    }
}