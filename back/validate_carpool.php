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
    $idTravel = $_POST['idTravel'];

    $rating = $_POST['rating'] ?? null;
    if ($rating === '')
        $rating = null;

    $comment = $_POST['comment'] ?? null;

    $travel = new Travel($pdo, $idTravel);
    $idDriver = $travel->getDriverId();

    if (isset($rating)) {
        $newRating = new Rating($pdo, $idPassenger, $idDriver, $rating, $comment);
        $newRating->saveRatingToDatabase($pdo, $idPassenger, $idDriver, $rating, $comment);
        echo "note attribuée !";
    } else {
        echo "pas de note attribuée.";
    }
    $reservation = new Reservation($pdo, $idPassenger, $idTravel);
    try {
        $reservation->validateCarpoolYes($pdo, $idPassenger, $idDriver, $idTravel);
    } catch (Exception $e) {
        echo "erreur dans la function validateCarpoolYes : " . $e->getMessage();
    }
}

//If the passenger has validated the carpool but not happy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'negative') {
    $idTravel = $_POST['idTravel'];
    $comment = $_POST['comment'];

    $reservation = new Reservation($pdo, $idPassenger, $idTravel, null, $comment);
    try {
        $reservation->validateCarpoolNo($pdo, $idPassenger, $idTravel, $comment);
    } catch (Exception $e) {
        echo "erreur dans la function validateCarpoolNo : " . $e->getMessage();
    }

}