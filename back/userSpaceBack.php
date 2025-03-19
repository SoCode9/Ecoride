<?php

require_once "../database.php";
require_once "../class/User.php";
require_once "../class/Driver.php";
require_once "../class/Car.php";
require_once "../class/Reservation.php";

$idUser = $_SESSION['user_id'];

$connectedUser = new User($pdo, $idUser, null, null, null);
if (($connectedUser->getIdRole() === 2) or ($connectedUser->getIdRole() === 3)) {
    $connectedDriver = new Driver($pdo, $connectedUser->getId());
    $carsOfConnectedDriver = new Car($pdo, $connectedDriver->getId(), null);
    $cars = $carsOfConnectedDriver->getCars();
}

$usersReservations = new Reservation($pdo, $idUser);
$carpoolListToValidate = $usersReservations->carpoolFinishedToValidate($pdo, $idUser);

$carpoolListNotStarted = $usersReservations->carpoolNotStarted($pdo, $idUser);

$carpoolListFinishedAndValidated = $usersReservations->carpoolFinishedAndValidated($pdo, $idUser);

/*Cars' form*/
// Request to retrieve car's brands 
$stmt = $pdo->query("SELECT id, name FROM brands ORDER BY name ASC");
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === "formCar")) {
    $licencePlate = $_POST['licence_plate'];
    $firstRegistrationDate = $_POST['first_registration_date'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $electric = $_POST['electric'];
    $color = $_POST['color'];
    $seatOffered = $_POST['nb_passengers'];
    try {
        $newCar = new Car($pdo, $idUser, null);
        $newCar->createCar($pdo, $idUser, $brand, $model, $licencePlate, $firstRegistrationDate, $seatOffered, $electric, $color);

    } catch (Exception $e) {
        echo "Exception attrappée : " . $e->getMessage();
    }
}