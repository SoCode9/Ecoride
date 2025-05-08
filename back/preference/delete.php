<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Driver.php";

$prefToDelete = $_GET['id'];
$driverId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'delete_pref') {
    try {
        $driver = new Driver($pdo, $driverId);
        $customPreferencesInDB = $driver->loadCustomPreferences();

        foreach ($customPreferencesInDB as $columnName => $value) {
            if ($value === $prefToDelete) {
                $sql = "UPDATE driver SET $columnName = NULL WHERE user_id = :driverId";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':driverId', $driverId, PDO::PARAM_INT);
                $statement->execute();
                header('Location:../../controllers/user_space.php');
                exit;
            }
        }

    } catch (Exception $e) {
        error_log("Error in delete preference (driver ID: {$driverId}) : " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue";
        header('Location: ../../controllers/user_space.php');
        exit;
    }
}