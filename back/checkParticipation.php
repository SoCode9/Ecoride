<?php

require_once "../database.php";
require_once "../class/Reservation.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$seatsAllocated = $reservation->nbPassengerInACarpool($pdo, $travelId);
$stmt = $pdo->prepare("SELECT seats_offered - $seatsAllocated AS availableSeats, travel_price FROM travels WHERE id = ?"); // "?" => 'travel_id'
$stmt->execute([$travelId]);
$travel = $stmt->fetch();

if (!$travel) {
    echo json_encode(["success" => false, "message" => "Covoiturage introuvable."]);
    exit;
}

$availableSeats = (int) $travel['availableSeats'];
$travelPrice = (int) $travel['travel_price'];

// Return nb available seats in json
echo json_encode(["success" => true, "availableSeats" => $availableSeats, "userCredits" => $userCredit, "travelPrice" => $travelPrice]);
