<?php

require_once "../database.php";
require_once "../class/Travel.php";
require_once "../class/Reservation.php";
require_once "../class/Rating.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idPassenger = $_SESSION['user_id'];

//If the passenger has validated the carpool (with or without a rating)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'positive') {
    $reservationId = $_POST['idReservation'];

    $rating = $_POST['rating'] ?? null;
    if ($rating === '')
        $rating = null;

    $comment = $_POST['comment'] ?? null;
    /* 
        $travel = new Travel($pdo, $idTravel);
        $idDriver = $travel->getDriverId(); */

    $reservation = new Reservation($pdo);
    $driverId = $reservation->getDriverIdFromReservation($pdo, $reservationId);
 

    if (isset($rating)) {
        $newRating = new Rating($pdo);
        $newRating->saveRatingToDatabase($pdo, $idPassenger, $driverId, $rating, $comment);
        echo "note attribuée !"; // A ENLEVER ?
    } else {
        echo "pas de note attribuée.";// A ENLEVER ?
    }
    try {
        $reservation->validateCarpoolYes($pdo, $reservationId);
    } catch (Exception $e) {
        echo "erreur dans la function validateCarpoolYes : " . $e->getMessage();
    }
}

//If the passenger has validated the carpool but not happy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'negative') {
    $reservationId = $_POST['idReservation'];
    $comment = $_POST['comment'];

    $reservation = new Reservation($pdo);
    try {
        $reservation->validateCarpoolNo($pdo, $reservationId, $comment);
    } catch (Exception $e) {
        echo "erreur dans la function validateCarpoolNo : " . $e->getMessage();
    }

}