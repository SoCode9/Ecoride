<?php

require_once "../database.php";
require_once "../class/Travel.php";
require_once "../class/Driver.php";
require_once "../class/Car.php";

/**To get all the driver's cars */

$driverId = $_SESSION['user_id'];
$driver = new Driver($pdo, $driverId);
$cars = new Car($pdo, $driverId);
$carsOfDriver = $cars->getCars();

/* 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //Ajouter id_driver ?
    $travelDate = $_POST["travelDate"];
    $travelDepartureCity = $_POST["travelDepartureCity"];
    $travelArrivalCity = $_POST["travelArrivalCity"];
    $travelDepartureTime = $_POST["travelDepartureTime"];
    $travelArrivalTime = $_POST["travelArrivalTime"];
    $travelPrice = $_POST["travelPrice"];
    $placesOffered = $_POST["placesOffered"];
    $carId = $_POST["carID"];

    $newTravel = new Travel($pdo);
    if ($newTravel->saveTravelToDatabase()) {
        echo 'Le voyage a bien été publié';
    } else {
        echo 'Erreur lors de la création du voyage';
    }
    ;
}
 */