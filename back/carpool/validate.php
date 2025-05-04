<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Travel.php";
require_once __DIR__ . "/../../class/Rating.php";
require_once __DIR__ . "/../../class/Reservation.php";

$passengerId = $_SESSION['user_id'];

//If the passenger has validated the carpool (with or without a rating)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'positive') {
    $reservationId = $_POST['idReservation'];

    $rating = $_POST['rating'] ?? null;
    if ($rating === '')
        $rating = null;

    $comment = $_POST['comment'] ?? null;

    $reservation = new Reservation($pdo);
    $driverId = $reservation->getDriverIdFromReservation($pdo, $reservationId);


    if (isset($rating)) {
        $newRating = new Rating($pdo);
        $newRating->saveRatingToDatabase($pdo, $passengerId, $driverId, $rating, $comment);
    } 
    try {
        $reservation->validateCarpoolYes($pdo, $reservationId);
        header('Location:../../controllers/user_space?tab=carpools.php');
    } catch (Exception $e) {
        echo "Erreur dans la function validateCarpoolYes : " . $e->getMessage();
    }
}

//If the passenger has validated the carpool but not happy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'negative') {
    $reservationId = $_POST['idReservation'];
    $comment = $_POST['comment'];

    $reservation = new Reservation($pdo);
    try {
        $reservation->validateCarpoolNo($pdo, $reservationId, $comment);
        header('Location:../../controllers/user_space?tab=carpools.php');
    } catch (Exception $e) {
        echo "erreur dans la function validateCarpoolNo : " . $e->getMessage();
    }

}