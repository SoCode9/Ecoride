<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
requireDriver(); // Checks whether the user is a driver
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Travel.php";

$pdo = pdo();

/**To get all the driver's cars */
$driverId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1) Required fields
    $required = [
        'travel-date',
        'departure-city-search',
        'arrival-city-search',
        'travel-departure-time',
        'travel-arrival-time',
        'travel-price',
        'carSelected',
    ];

    $errors = [];
    $old = [];

    foreach ($required as $name) {
        $value = $_POST[$name] ?? '';
        $old[$name] = $value;
        if (trim((string)$value) === '') {
            $errors[$name] = "Ce champ est obligatoire.";
        }
    }

    // 2) Other validations
    if (!isset($errors['travel-date'])) {
        $today = date('Y-m-d');
        if (trim($_POST['travel-date']) < $today) {
            $errors['travel-date'] = "La date ne peut pas être dans le passé.";
        }
    }

    if (!isset($errors['travel-price'])) {
        // entier ≥ 2
        if (!ctype_digit($_POST['travel-price']) || $_POST['travel-price'] < 2) {
            $errors['travel-price'] = "Le prix doit être un entier positif supérieur ou égal à 2.";
        }
    }

    if (
        !isset($errors['departure-city-search']) &&
        !isset($errors['arrival-city-search']) &&
        mb_strtolower(trim($_POST['departure-city-search'])) === mb_strtolower(trim($_POST['arrival-city-search']))
    ) {
        $errors['arrival-city-search'] = "La ville d'arrivée doit être différente de la ville de départ.";
    }

    if (
        !isset($errors['travel-departure-time']) &&
        !isset($errors['travel-arrival-time']) &&
        (trim($_POST['travel-departure-time'])) >= mb_strtolower(trim($_POST['travel-arrival-time']))
    ) {
        $errors['travel-arrival-time'] = "L'heure d'arrivée doit être supérieure à l'heure de départ.";
    }

    // 3) If error → feedback + retour
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_old'] = $old;
        $_SESSION['error_message'] = "Veuillez corriger les erreurs de complétion du formulaire.";
        header('Location: ../../controllers/create_carpool.php');
        exit;
    }

    // 4) OK -> creation
    try {
        $travelDate = $_POST["travel-date"];
        $travelDepartureCity = $_POST["departure-city-search"];
        $travelArrivalCity = $_POST["arrival-city-search"];
        $travelDepartureTime = $_POST["travel-departure-time"];
        $travelArrivalTime = $_POST["travel-arrival-time"];
        $travelPrice = $_POST["travel-price"];
        $carSelectedId = $_POST["carSelected"];
        $travelComment = $_POST["comment"];

        $newTravel = new Travel($pdo);
        $newTravel->createNewTravel(
            $driverId,
            $travelDate,
            $travelDepartureCity,
            $travelArrivalCity,
            $travelDepartureTime,
            $travelArrivalTime,
            $travelPrice,
            $carSelectedId,
            $travelComment
        );

        $_SESSION['success_message'] = 'Le voyage a bien été publié.';
    } catch (Exception $e) {
        error_log("Error in create_carpool.php (user ID: " . ($_SESSION['user_id'] ?? 'inconnu') . ") : " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue lors de la création du covoiturage.";
    }

    header('Location: ../../controllers/user_space.php?tab=carpools');
    exit;
}
