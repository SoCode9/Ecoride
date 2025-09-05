<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Reservation.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";
require_once __DIR__ . "/../../class/User.php";

$pdo = pdo();

try {
    $pdo->beginTransaction();

    ### second check ###
    $userId = $_SESSION['user_id'];

    $travelId = $_POST['travel_id'] ?? null;

    // Check if travel ID is sent
    if (!isset($travelId)) {
        throw new Exception("ID du covoiturage manquant");
    }

    // Check the available seats and retrieve the carpool's price
    $reservation = new Reservation($pdo, $userId, $travelId);
    $car = new Car($pdo, null, $travelId);
    $travel = new Travel($pdo, $travelId);

    $seatsAllocated = (int)$reservation->countPassengers($travelId);
    $seatsOffered = (int)$car->getSeatsOfferedByCar($travel->getCarId());
    $availableSeats = max(0, $seatsOffered - $seatsAllocated);
    if ($availableSeats = 0) {
        throw new Exception("Plus de places disponibles");
    }
    $travelPrice = (int)$travel->getPrice();

    // Check the carpool's status
    if ($travel->getStatus() !== 'not started') {
        throw new Exception("Impossible de participer à ce covoiturage");
    }

    // Check that the user has enough credits 
    $user = User::fromId($pdo, $userId);
    $userCredit = (int)$user->getCredit();

    if ($userCredit < $travelPrice) {
        throw new Exception("Crédits insuffisants");
    }

    ### END second check ###

    // UPDATE DataBase 

    //debit the user
    $user->setCredit($travelPrice);

    //create the reservation in DB
    $reservation->createNewReservation($userId, $travelId, $travelPrice);

    $pdo->commit();
    echo json_encode(["success" => true, "message" => "Participation confirmée !"]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error in update_participation.php : " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Une erreur est survenue"
    ]);
}
