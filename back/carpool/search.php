<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Travel.php";
require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../class/Driver.php";
require_once __DIR__ . "/../../class/Reservation.php";


try {
    // form actions
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action'])) {
        switch ($_POST['action']) {
            case "search":
                $_SESSION['departure-date-search'] = $_POST["departure-date-search"];
                $_SESSION['departure-city-search'] = $_POST["departure-city-search"];
                $_SESSION['arrival-city-search'] = $_POST["arrival-city-search"];
                break;

            case "filters":
                $_SESSION['eco'] = isset($_POST['eco']) ? 1 : null;
                $_SESSION['max-price'] = $_POST["max-price"] !== "" ? (int) $_POST["max-price"] : null;
                $_SESSION['max-duration'] = $_POST["max-duration"] !== "" ? (int) $_POST["max-duration"] : null;
                $_SESSION['driver-rating-list'] = isset($_POST["driver-rating-list"]) ? floatval($_POST["driver-rating-list"]) : null;
                break;

            case "reset_filters":
                unset($_SESSION['max-price'], $_SESSION['max-duration'], $_SESSION['eco'], $_SESSION['driver-rating-list']);
                break;
        }
    }

    $dateSearch = $_SESSION['departure-date-search'] ?? null;
    $departureCitySearch = $_SESSION["departure-city-search"] ?? null;
    $arrivalCitySearch = $_SESSION["arrival-city-search"] ?? null;
    $eco = $_SESSION["eco"] ?? null;
    $maxPrice = $_SESSION["max-price"] ?? null;
    $maxDuration = $_SESSION["max-duration"] ?? null;
    $driverRating = $_SESSION["driver-rating-list"] ?? null;


    $travel = new Travel($pdo);
    $reservation = new Reservation($pdo);
    $car = new Car($pdo);

    $travelsSearched = $travel->searchTravels(
        dateSearch: $dateSearch,
        departureCitySearch: $departureCitySearch,
        arrivalCitySearch: $arrivalCitySearch,
        eco: $eco,
        maxPrice: $maxPrice,
        maxDuration: $maxDuration,
        driverRating: $driverRating
    );

    if (empty($travelsSearched)) {
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

    // Save the result in the session to be retrieved by carpool-search.php
    $_SESSION['travelsSearched'] = $travelsSearched;
    $_SESSION['nextTravelDate'] = $nextTravelDate ?? [];

} catch (Exception $e) {
    error_log("Error in search carpool : " . $e->getMessage());
    header('Location:../../controllers/carpool_search.php');
    $_SESSION['error_message'] = "Une erreur est survenue";
}

