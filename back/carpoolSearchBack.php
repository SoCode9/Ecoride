<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



require_once "../database.php";
require_once "../class/Travel.php";
require_once "../class/User.php";
require_once "../class/Driver.php";
require_once "../class/Reservation.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {

    if ($_POST['action'] === "search") {
        $_SESSION['departure-date-search'] = $_POST["departure-date-search"];
        $_SESSION['departure-city-search'] = $_POST["departure-city-search"];
        $_SESSION['arrival-city-search'] = $_POST["arrival-city-search"];

    } elseif ($_POST['action'] === "filters") {
        $_SESSION['eco'] = isset($_POST['eco']) ? 1 : null;
        $_SESSION['max-price'] = isset($_POST["max-price"]) && $_POST["max-price"] !== "" ? (int) $_POST["max-price"] : null;
        $_SESSION['max-duration'] = isset($_POST["max-duration"]) && $_POST["max-duration"] !== "" ? (int) $_POST["max-duration"] : null;
        $_SESSION['driver-rating-list'] = isset($_POST["driver-rating-list"]) ? floatval($_POST["driver-rating-list"]) : null;

    } elseif ($_POST['action'] === "reset_filters") {
        unset($_SESSION['max-price'], $_SESSION['max-duration'], $_SESSION['eco'], $_SESSION['driver-rating-list']);
    }
}

$dateSearch = $_SESSION['departure-date-search'] ?? null;
$departureCitySearch = $_SESSION["departure-city-search"] ?? null;
$arrivalCitySearch = $_SESSION["arrival-city-search"] ?? null;
$eco = $_SESSION["eco"] ?? null;
$maxPrice = $_SESSION["max-price"] ?? null;
$maxDuration = $_SESSION["max-duration"] ?? null;
$driverRating = $_SESSION["driver-rating-list"] ?? null;

try {
    $travel = new Travel($pdo);
    $travelsSearched = $travel->searchTravels(
        dateSearch: $dateSearch,
        departureCitySearch: $departureCitySearch,
        arrivalCitySearch: $arrivalCitySearch,
        eco: $eco,
        maxPrice: $maxPrice,
        maxDuration: $maxDuration,
        driverRating: $driverRating
    );
    $reservation = new Reservation($pdo);

    if (!$travelsSearched) {
        // if no travel is found, search for the next travel that matches the criteria
        $nextTravelDate = $travel->searchnextTravelDate(
            dateSearch: $dateSearch,
            departureCitySearch: $departureCitySearch,
            arrivalCitySearch: $arrivalCitySearch,
            eco: $eco,
            maxPrice: $maxPrice,
            maxDuration: $maxDuration,
            driverRating: $driverRating
        );
    }

    // Save the result in the session to be retrieved by carpoolSearch.php
    $_SESSION['travelsSearched'] = $travelsSearched;
    $_SESSION['nextTravelDate'] = $nextTravelDate ?? [];

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
}

