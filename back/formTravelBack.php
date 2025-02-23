<?php

require_once "database.php";
require_once "../class/Travel.php";

//WORKS ! 
//TODO : carID --> display the driver's cars
//form to propose a travel

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

    $newTravel = new Travel($travelDate, $travelDepartureCity, $travelArrivalCity, $travelDepartureTime, $travelArrivalTime, $travelPrice, $placesOffered, $carId);
    if ($newTravel->saveTravelToDatabase()) {
        echo 'Le voyage a bien été publié';
    } else {
        echo 'Erreur lors de la création du voyage';
    }
    ;
}

?>