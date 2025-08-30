<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";
require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../class/Driver.php";
require_once __DIR__ . "/../../class/User.php";

$pdo = pdo();

$userId = $_SESSION['user_id'] ?? null;

try {
    if (isset($_GET['id'])) {
        $travelId = $_GET['id'];
    }
    $travel = new Travel($pdo, $travelId);
    $driver = new Driver($pdo, $travel->getDriverId());
    $car = new Car($pdo, null, $travelId);
    if ($userId !== null) {
        $user = User::fromId($pdo, $userId);
    }

} catch (Exception $e) {
    error_log("Error in details carpool : " . $e->getMessage());
    header('Location:../../controllers/carpool_details.php');
    $_SESSION['error_message'] = "Une erreur est survenue";
    exit;
}