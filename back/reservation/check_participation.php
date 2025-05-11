<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Reservation.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";

try {
    // check if travel ID is sent
    if (!isset($_POST['travel_id'])) {
        throw new Exception("ID du covoiturage manquant");
    }

    $travelId = $_POST['travel_id'];
    $userId = $_SESSION['user_id'] ?? null;

    // Check that the user is logged in
    if (!isset($userId)) {
        throw new Exception("Utilisateur non connecte");
    }

    $statement = $pdo->prepare('SELECT id FROM reservations WHERE user_id = :userId AND travel_id = :travelId');
    $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
    $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
    $statement->execute();
    $reservationAlreadyDone = $statement->fetch();
    if ($reservationAlreadyDone) {
        throw new Exception("Utilisateur déjà inscrit à ce covoiturage");
    }

    // Retrieve user's credits
    $statement = $pdo->prepare("SELECT credit FROM users where id = ?");
    $statement->execute([$userId]);
    $user = $statement->fetch();

    if (!$user) {
        throw new Exception("Utilisateur introuvable");
    }

    $userCredit = (int) $user['credit'];


    // SQL query to retrieve available seats in real time 
    $reservation = new Reservation($pdo, $userId, $travelId);
    $car = new Car($pdo, null, $travelId);
    $newTravel = new Travel($pdo, $travelId);

    $seatsAllocated = $reservation->countPassengers($travelId);
    $seatsOffered = $car->getSeatsOfferedByCar($newTravel->getCarId());

    $statement = $pdo->prepare("SELECT :seatsOffered - :seatsAllocated AS availableSeats, travel_price FROM travels WHERE id = :travelId");
    $statement->bindValue(':seatsOffered', $seatsOffered, PDO::PARAM_INT);
    $statement->bindValue(':seatsAllocated', $seatsAllocated, PDO::PARAM_INT);
    $statement->bindValue(':travelId', $travelId, PDO::PARAM_STR);
    $statement->execute();
    $travel = $statement->fetch();

    if (!$travel) {
        throw new Exception("Covoiturage introuvable");
    }

    if ($newTravel->getStatus() !== 'not started') {
        throw new Exception("Le covoiturage est soit en cours, soit annulé, soit terminé");
    }
    echo json_encode([
        "success" => true,
        "availableSeats" => (int) $travel['availableSeats'],
        "userCredits" => $userCredit,
        "travelPrice" => (int) $travel['travel_price']
    ]);
} catch (Exception $e) {
    error_log("Error in check_participation.php : " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Une erreur est survenue"
    ]);
}