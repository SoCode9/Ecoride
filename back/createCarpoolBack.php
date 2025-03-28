<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";
require_once "../class/Travel.php";
require_once "../class/Driver.php";
require_once "../class/Car.php";

/**To get all the driver's cars */

$driverId = $_SESSION['user_id'];
$driver = new Driver($pdo, $driverId);
$cars = new Car($pdo, $driverId);
$carsOfDriver = $cars->getCars();


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $travelDate = $_POST["travelDate"];
    $travelDepartureCity = $_POST["departure-city-search"];
    $travelArrivalCity = $_POST["arrival-city-search"];
    $travelDepartureTime = $_POST["travelDepartureTime"];
    $travelArrivalTime = $_POST["travelArrivalTime"];
    $travelPrice = $_POST["travelPrice"];
    $carSelectedId = $_POST["carSelected"];
    $travelComment = $_POST["comment"];

    $newTravel = new Travel($pdo);
    if ($newTravel->saveTravelToDatabase($pdo, $driverId, $travelDate, $travelDepartureCity, $travelArrivalCity, $travelDepartureTime, $travelArrivalTime, $travelPrice, $carSelectedId, $travelComment)) {
        $_SESSION['success_message'] = 'Le voyage a bien été publié';
        header('Location:../index/userSpaceIndex.php');
    } else {
        $_SESSION['error_message'] = 'Erreur lors de la création du voyage';
    }
    ;
}
