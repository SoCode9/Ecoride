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


$roleId = (int) $_POST['role_id'];
$userId = $_SESSION['user_id'] ?? null;

if ($roleId === null || !in_array($roleId, [1, 2, 3])) {
    echo json_encode(["success" => false, "message" => "Rôle invalide"]);
    exit;
}
function processPreference($preference, $preferenceName)
{
    if ($preference === "NULL") {
        return null; // Convert “NULL” to true NULL for SQL
    } elseif ($preference === "0" || $preference === "1") {
        return (int) $preference; // Convert in int
    } else {
        echo json_encode(["success" => false, "message" => "Préférence $preferenceName invalide"]);
        exit;
    }
}
$smokePref = processPreference($_POST['smoke_pref'] ?? null, "fumeur");
$petPref = processPreference($_POST['pet_pref'] ?? null, "animaux");
$foodPref = processPreference($_POST['food_pref'] ?? null, "nourriture");
$speakPref = processPreference($_POST['speak_pref'] ?? null, "discussion");

try {
    $user = new User($pdo, $userId);
    $driver = new Driver($pdo, $userId);
    $user->setIdRole($roleId);
    $driver->setSmokerPreference($smokePref);
    $driver->setPetPreference($petPref);
    $driver->setFoodPreference($foodPref);
    $driver->setSpeakPreference($speakPref);

    echo json_encode(["success" => true, "message" => "Rôle et préférences mis à jour"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur : " . $e->getMessage()]);
}
