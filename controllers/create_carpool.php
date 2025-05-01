<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once '../back/carpool/create.php';

$pageTitle = "Proposer un covoiturage";
$customScript = "carpool_search.js";
$templatePage = "pages/create_carpool.php";

include __DIR__ . "/../templates/layout.php";

?>

<style>
    #userSpace {
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }
</style>