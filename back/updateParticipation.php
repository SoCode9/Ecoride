<?php
require_once "../database.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
$travelId = intval($_POST['travel_id']);

// Check that carpooling exists and get the price
$reservation = new Reservation ($pdo,$userId,$travelId);
$seatsAllocated = $reservation->nbPassengerInACarpool($pdo,$travelId);
$stmt = $pdo->prepare("SELECT seats_offered - $seatsAllocated AS availableSeats, travel_price FROM travels WHERE id = ?");
$stmt->execute([$travelId]);
$travel = $stmt->fetch();

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

// Check that the user has enough credits 
$stmt = $pdo->prepare("SELECT credit FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

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
    $stmt = $pdo->prepare("UPDATE users SET credit = credit - ? WHERE id = ?");
    $stmt->execute([$travelPrice, $userId]);

    /* // Update nb seats_allocated
    $stmt = $pdo->prepare("UPDATE travels SET seats_allocated= seats_allocated +1 WHERE id = ?");
    $stmt->execute([$travelId]); */

    $stmt = $pdo->prepare("INSERT INTO reservations (user_id,travel_id) VALUES (?,?)");
    $stmt->execute([$userId, $travelId]);


    $pdo->commit(); // validates the transaction

    echo json_encode(["success" => true, "message" => "Participation confirmée !"]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "message" => "Erreur BDD : " . $e->getMessage()]);

}