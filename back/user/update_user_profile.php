<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../class/Driver.php";

$pdo = pdo();

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
$musicPref = processPreference($_POST['music_pref'] ?? null, "musique");

try {
    $user = User::fromId($pdo, $userId);
    try {
        $driver = new Driver($pdo, $userId);
    } catch (Exception $e) {
        error_log("Driver not found, creating one for user $userId");
        $user->createDriver($userId);
        $driver = new Driver($pdo, $userId);
    }
    $user->setIdRole($roleId);
    $driver->setSmokerPreference($smokePref);
    $driver->setPetPreference($petPref);
    $driver->setFoodPreference($foodPref);
    $driver->setSpeakPreference($speakPref);
    $driver->setMusicPreference($musicPref);
    $_SESSION['role_user'] = $roleId;
    echo json_encode(["success" => true, "message" => "Rôle et préférences mis à jour"]);
    $_SESSION['success_message'] = "Profil mis à jour";
    exit;
} catch (Exception $e) {
    error_log("Erreur update profil (user ID: $userId) : " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Impossible de mettre à jour votre profil"]);
}
