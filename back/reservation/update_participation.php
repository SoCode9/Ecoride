<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Reservation.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";

$pdo = pdo();

### second check ###

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Utilisateur non connecte");
    }

    if (!isset($_POST['travel_id'])) {
        throw new Exception("ID du covoiturage manquant");
    }

    $userId = $_SESSION['user_id'];
    $travelId = $_POST['travel_id'];

    // Check that carpooling exists and get the price
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
        throw new Exception("Impossible de participer à ce covoiturage");
    }

    $availableSeats = (int) $travel['availableSeats'];
    $travelPrice = (int) $travel['travel_price'];

    if ($availableSeats <= 0) {
        throw new Exception("Plus de places disponibles");
    }

    // Check that the user has enough credits 
    $statement = $pdo->prepare("SELECT credit FROM users WHERE id = ?");
    $statement->execute([$userId]);
    $user = $statement->fetch();

    if (!$user) {
        throw new Exception("Utilisateur introuvable");
    }

    $userCredits = (int) $user['credit'];

    if ($userCredits < $travelPrice) {
        throw new Exception("Crédits insuffisants");
    }
    ### END second check ###

    // UPDATE DataBase 

    $pdo->beginTransaction();

    //debit the user
    $statement = $pdo->prepare("UPDATE users SET credit = credit - ? WHERE id = ?");
    $statement->execute([$travelPrice, $userId]);

    $statement = $pdo->prepare("INSERT INTO reservations (user_id,travel_id, credits_spent) VALUES (?,?,?)");
    $statement->execute([$userId, $travelId, $travelPrice]);


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