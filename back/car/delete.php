<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
requireDriver(); // Checks whether the user is a driver
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Car.php";

$idDriver = $_SESSION['user_id'];

$car = new Car($pdo, $idDriver, null);
$idCarToDelete = $_GET['id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'delete_car') {
        $car->deleteCar($idCarToDelete);
        header('Location:../../controllers/user_space.php');
        $_SESSION['success_message'] = "La voiture a été supprimée";
        exit;
    }
} catch (Exception $e) {
    error_log("Error in delete a car (driver ID: $idDriver) : " . $e->getMessage());
    header('Location:../../controllers/user_space.php');
    $_SESSION['error_message'] = $e->getMessage();
    exit;
}
