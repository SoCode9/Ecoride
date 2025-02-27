<?php

session_start();

require_once "../database.php";
require_once "../class/Travel.php";
require_once "../class/User.php";
require_once "../class/Driver.php";


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if ($_POST['action'] === "search") {
        $_SESSION['departure-date-search'] = $_POST["departure-date-search"];
        $_SESSION['departure-city-search'] = $_POST["departure-city-search"];
        $_SESSION['arrival-city-search'] = $_POST["arrival-city-search"];

        // Reset filters if new search
        unset($_SESSION['max-price'], $_SESSION['max-duration'], $_SESSION['eco']);

    } elseif ($_POST['action'] === "filters") {
        $_SESSION['eco'] = isset($_POST['eco']) ? 1 : null;
        $_SESSION['max-price'] = isset($_POST["max-price"]) && $_POST["max-price"] !== "" ? (int) $_POST["max-price"] : null; //if not completed -> null. Elsif : convert in int(nb)
        $_SESSION['max-duration'] = isset($_POST["max-duration"]) && $_POST["max-duration"] !== "" ? (int) $_POST["max-duration"] : null; //if not completed -> null. Elsif : convert in int(nb)

        $_SESSION['driver-rating-list'] = isset($_POST["driver-rating-list"]) ? floatval($_POST["driver-rating-list"]) : null;

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
        if ($travel->searchTravels(dateSearch: $dateSearch, departureCitySearch: $departureCitySearch, arrivalCitySearch: $arrivalCitySearch, eco: $eco, maxPrice: $maxPrice, maxDuration: $maxDuration, driverRating: $driverRating)) {
            //echo 'la recherche a fonctionné !';
            $travelsSearched = $travel->searchTravels(dateSearch: $dateSearch, departureCitySearch: $departureCitySearch, arrivalCitySearch: $arrivalCitySearch, eco: $eco, maxPrice: $maxPrice, maxDuration: $maxDuration, driverRating: $driverRating);

        } else {
            echo 'Erreur lors de la recherche';
        }
    } catch (PDOException $e) {
        echo "Erreur précise : " . $e->getMessage();
    }
    ;

}