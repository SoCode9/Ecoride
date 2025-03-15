<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../database.php';
require_once "../class/Travel.php";
require_once "../class/Car.php";
require_once "../class/User.php";
require_once "../class/Driver.php";

?>

<?php

try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $travelId = intval($_GET['id']);
    }
    $travel = new Travel($pdo, $travelId);
    $driver = new Driver($pdo, $travel->getDriverId());
    $car = new Car($pdo, null, $travelId);
    //$user = new User($pdo, $_SESSION['user_id']);

} catch (Exception $e) {
    echo "Erreur : !!" . $e->getMessage();
}
