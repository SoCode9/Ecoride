<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";
require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../class/Driver.php";
require_once __DIR__ . "/../../class/User.php";

$userId = $_SESSION['user_id'] ?? null;

try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $travelId = intval($_GET['id']);
    }
    $travel = new Travel($pdo, $travelId);
    $driver = new Driver($pdo, $travel->getDriverId());
    $car = new Car($pdo, null, $travelId);

    $user = new User($pdo, $userId);


} catch (Exception $e) {
    echo "Erreur : !!" . $e->getMessage();
}