<?php

require_once "../database.php";
require_once "../class/User.php";
require_once "../class/Driver.php";
require_once "../class/Car.php";
require_once "../class/Reservation.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

/*Cancel a carpool*/
if (isset($_GET['action'])) {
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'cancel_carpool') {
        $idTravel = $_GET['id'];
        $usersReservations->cancelCarpool($pdo, $idUser, $idTravel);
        header('Location: ../index/userSpaceIndex.php');
    }
}
