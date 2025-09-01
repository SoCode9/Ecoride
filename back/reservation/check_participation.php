<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Reservation.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";
require_once __DIR__ . "/../../class/User.php";

$pdo = pdo();

try {
    // Check if travel ID is sent
    if (!isset($_POST['travel_id'])) {
        throw new Exception("ID du covoiturage manquant");
    }
    $travelId = $_POST['travel_id'];
    $userId = $_SESSION['user_id'] ?? null;

    // Check that the user is logged in
    if (!isset($userId)) {
        throw new Exception("Utilisateur non connecte");
    }

    // Check if user is already a passenger
    $reservation = new Reservation($pdo, $userId, $travelId);
    if ($reservation->existsForUserAndTravel($userId, $travelId)) {
        throw new Exception("Utilisateur déjà inscrit à ce covoiturage");
    }

    // Retrieve user's credits
    $user = User::fromId($pdo, $userId);
    $userCredit = (int)$user->getCredit();

    // Check the available seats and retrieve the carpool's price
    $travel = new Travel($pdo, $travelId);
    $seatsAllocated = (int)$reservation->countPassengers($travelId);
    $car = new Car($pdo, null, $travelId);
    $seatsOffered = (int)$car->getSeatsOfferedByCar($travel->getCarId());
    $availableSeats = max(0, $seatsOffered - $seatsAllocated);

    $travelPrice = (int)$travel->getPrice();

    // Check the carpool's status
    if ($travel->getStatus() !== 'not started') {
        throw new Exception("Le covoiturage est soit en cours, soit annulé, soit terminé");
    }

    echo json_encode([
        "success" => true,
        "availableSeats" => $availableSeats,
        "userCredits" => $userCredit,
        "travelPrice" => $travelPrice
    ]);
} catch (Exception $e) {
    error_log("Error in check_participation.php : " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
