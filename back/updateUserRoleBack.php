<?php

require_once "../database.php";
require_once "../class/User.php";
require_once "../class/Driver.php";

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
$smokePref = isset($_POST['smoke_pref']) ? $_POST['smoke_pref'] : null;

if ($roleId === null || !in_array($roleId, [1, 2, 3])) {
    echo json_encode(["success" => false, "message" => "Rôle invalide"]);
    exit;
}

if ($smokePref === "NULL") {
    $smokePrefValue = null; // Convertir en vrai NULL pour SQL
} elseif ($smokePref === "0" || $smokePref === "1") {
    $smokePrefValue = (int) $smokePref; // Convertir en entier
} else {
    echo json_encode(["success" => false, "message" => "Préférence fumeur invalide"]);
    exit;
}

try {
    $user = new User($pdo, $userId);
    $driver = new Driver($pdo, $userId);
    $user->setIdRole($roleId);
    $driver->setSmokerPreference($smokePrefValue);

    echo json_encode(["success" => true, "message" => "Rôle mis à jour"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur : " . $e->getMessage()]);
}
