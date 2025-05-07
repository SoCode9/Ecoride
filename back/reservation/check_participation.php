<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Reservation.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";


// check if travel ID is sent
if (!isset($_POST['travel_id'])) {
    echo json_encode(["success" => false, "message" => "ID du covoiturage manquant"]);
    exit;
}

$travelId = $_POST['travel_id'];
$userId = $_SESSION['user_id'] ?? null;

// Check that the user is logged in
if (!isset($userId)) {
    echo json_encode(["success" => false, "message" => "Utilisateur non connecte"]);
    exit;
}

$statement = $pdo->prepare('SELECT id FROM reservations WHERE user_id = :userId AND travel_id = :travelId');
$statement->bindParam(':userId', $userId, PDO::PARAM_INT);
$statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
$statement->execute();
$reservationAlreadyDone = $statement->fetch();
if ($reservationAlreadyDone) {
    echo json_encode(["success" => false, "message" => "Utilisateur déjà inscrit à ce covoiturage"]);
    exit;
}

// Retrieve user's credits
$stmt = $pdo->prepare("SELECT credit FROM users where id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success" => false, "message" => "Utilisateur introuvable"]);
    exit;
}

$userCredit = (int) $user['credit'];


// SQL query to retrieve available seats in real time 
$reservation = new Reservation($pdo, $userId, $travelId);
$car = new Car($pdo, null, $travelId);
$newTravel = new Travel($pdo, $travelId);
$seatsAllocated = $reservation->countPassengers( $travelId);
$seatsOffered = $car->nbSeatsOfferedInACarpool($pdo, $newTravel->getCarId());
$stmt = $pdo->prepare("SELECT $seatsOffered - $seatsAllocated AS availableSeats, travel_price FROM travels WHERE id = ?"); // "?" => 'travel_id'
$stmt->execute([$travelId]);
$travel = $stmt->fetch();

if (!$travel) {
    echo json_encode(["success" => false, "message" => "Covoiturage introuvable."]);
    exit;
}

if ($newTravel->getStatus() !== 'not started') {
    echo json_encode(["success" => false, "message" => "Le covoiturage est soit en cours, soit annulé, soit terminé."]);
    exit;
}

$availableSeats = (int) $travel['availableSeats'];
$travelPrice = (int) $travel['travel_price'];

// Return nb available seats in json
echo json_encode(["success" => true, "availableSeats" => $availableSeats, "userCredits" => $userCredit, "travelPrice" => $travelPrice]);
