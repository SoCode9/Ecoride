<?php

/* file_put_contents("debug-form.txt", print_r($_POST, true));
echo "REQUETE BIEN RECUE"; */


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
    echo "id travel : " . $idTravel;//A ENLEVER 

    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? null;
    echo "note laissé : " . $rating;
    echo "commentaire laissé : " . $comment;

    $travel = new Travel($pdo, $idTravel);
    $idDriver = $travel->getDriverId();

    echo "id Driver : " . $idDriver;//A ENLEVER 

    if (isset($rating)) {
        $newRating = new Rating($pdo, $idPassenger, $idDriver, $rating, $comment);
        $newRating->saveRatingToDatabase($pdo, $idPassenger, $idDriver, $rating, $comment);
        echo "note attribuée !";
    } else {
        echo "pas de note attribuée.";
    }
    $reservation = new Reservation($pdo, $idPassenger, $idTravel);
    try {
        $reservation->validateCarpool($pdo, $idPassenger, $idDriver, $idTravel);
    } catch (Exception $e) {
        echo "erreur dans la function validateCarpool : " . $e->getMessage();
    }
}