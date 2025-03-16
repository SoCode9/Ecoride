<?php

require_once "../database.php";
require_once "../class/User.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// check if role ID is sent
if (!isset($_POST['role_id'])) {
    echo json_encode(["success" => false, "message" => "ID du rôle manquant"]);
    exit;
}

$roleId = isset($_POST['role_id']) ? (int) $_POST['role_id'] : null;
$userId = $_SESSION['user_id'] ?? null;

if ($roleId === null || !in_array($roleId, [1, 2, 3])) {
    echo json_encode(["success" => false, "message" => "Rôle invalide"]);
    exit;
}
try {
    $user = new User($pdo, $userId);
    $user->setIdRole($roleId);

    echo json_encode(["success" => true, "message" => "Rôle mis à jour"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur : " . $e->getMessage()]);
}
