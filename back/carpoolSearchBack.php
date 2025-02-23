<?php

require_once "../database.php";
require_once "../class/Travel.php";

$travel = new Travel($pdo);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $dateSearch = $_POST["departure-date-search"];
    $departureCitySearch = $_POST["departure-city-search"];
    $arrivalCitySearch = $_POST["arrival-city-search"];

    try {
        if ($travel->searchTravels(dateSearch: $dateSearch, departureCitySearch: $departureCitySearch, arrivalCitySearch: $arrivalCitySearch)) {
            //echo 'la recherche a fonctionné !';
            $travelsSearched = $travel->searchTravels(dateSearch: $dateSearch, departureCitySearch: $departureCitySearch, arrivalCitySearch: $arrivalCitySearch);

        } else {
            echo 'Erreur lors de la recherche';
        }
    } catch (PDOException $e) {
        echo "Erreur précise : " . $e->getMessage();
    }
    ;

}