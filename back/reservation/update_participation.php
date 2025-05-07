<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Reservation.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";

### second check ###

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Utilisateur non connecté."]);
    exit;
}

if (!isset($_POST['travel_id'])) {
    echo json_encode(["success" => false, "message" => "ID du covoiturage manquant."]);
    exit;
}

$userId = $_SESSION['user_id'];
$travelId = $_POST['travel_id'];

// Check that carpooling exists and get the price
$reservation = new Reservation($pdo, $userId, $travelId);
$car = new Car($pdo, null, $travelId);
$newTravel = new Travel($pdo, $travelId);
$seatsAllocated = $reservation->countPassengers( $travelId);
$seatsOffered = $car->getSeatsOfferedByCar($newTravel->getCarId());
$statement = $pdo->prepare("SELECT $seatsOffered - $seatsAllocated AS availableSeats, travel_price FROM travels WHERE id = ?");
$statement->execute([$travelId]);
$travel = $statement->fetch();

if (!$travel) {
    echo json_encode(["success" => false, "message" => "Covoiturage introuvable."]);
    exit;
}

$availableSeats = (int) $travel['availableSeats'];
$travelPrice = (int) $travel['travel_price'];

if ($availableSeats <= 0) {
    echo json_encode(["success" => false, "message" => "Plus de places disponibles."]);
    exit;
}

if ($newTravel->getStatus() !== 'not started') {
    echo json_encode(["success" => false, "message" => "Impossible de participer au covoiturage."]);
    exit;
}

// Check that the user has enough credits 
$statement = $pdo->prepare("SELECT credit FROM users WHERE id = ?");
$statement->execute([$userId]);
$user = $statement->fetch();

if (!$user) {
    echo json_encode(["success" => false, "message" => "Utilisateur introuvable."]);
    exit;
}

$userCredits = (int) $user['credit'];

if ($userCredits < $travelPrice) {
    echo json_encode(["success" => false, "message" => "Crédits insuffisants."]);
    exit;
}
### END second check ###

// UPDATE DataBase 
try {
    $pdo->beginTransaction();

    //debit the user
    $statement = $pdo->prepare("UPDATE users SET credit = credit - ? WHERE id = ?");
    $statement->execute([$travelPrice, $userId]);

    $statement = $pdo->prepare("INSERT INTO reservations (user_id,travel_id, credits_spent) VALUES (?,?,?)");
    $statement->execute([$userId, $travelId, $travelPrice]);


    $pdo->commit(); // validates the transaction

    echo json_encode(["success" => true, "message" => "Participation confirmée !"]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "message" => "Erreur BDD : " . $e->getMessage()]);

}