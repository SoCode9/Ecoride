<?php

require_once "../database.php";

// check if travel ID is sent
if (!isset($_POST['travel_id'])) {
    echo json_encode(["success" => false, "message" => "ID du covoiturage manquant."]);
    exit;
}

$travelId = $_POST['travel_id'];

// SQL query to retrieve available seats in real time 
$stmt = $pdo->prepare("SELECT seats_offered - seats_allocated AS availableSeats FROM travels WHERE id = ?");
$stmt->execute([$travelId]);
$travel = $stmt->fetch();

if (!$travel) {
    echo json_encode(["success" => false, "message" => "Covoiturage introuvable."]);
    exit;
}

$availableSeats = (int) $travel['availableSeats'];

// Return nb available seats in json
echo json_encode(["success" => true, "availableSeats" => $availableSeats]);
