<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Driver.php";
require_once __DIR__ . "/../../class/Travel.php";

/**To get all the driver's cars */
$driverId = $_SESSION['user_id'];
$driver = new Driver($pdo, $driverId);


if ($_SERVER['REQUEST_METHOD'] == "POST") {
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
