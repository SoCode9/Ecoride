<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
requireDriver(); // Checks whether the user is a driver
require_once __DIR__ . "/../../init.php";
require_once __DIR__ . "/../../class/Driver.php";

$pdo = MysqlConnection::getPdo();

$prefToDelete = $_GET['id'];
$driverId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'delete_pref') {
    try {
        $driver = new Driver($pdo, $driverId);
        $driver->deleteCustomPreference($prefToDelete);

        $driver->deleteCustomPreference($prefToDelete);
        header('Location:../../controllers/user_space.php');
        $_SESSION['success_message'] = "La préférence a été supprimée";
    } catch (Exception $e) {
        error_log("Error in delete preference (driver ID: {$driverId}) : " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue";
        header('Location: ../../controllers/user_space.php');
        exit;
    }
}
