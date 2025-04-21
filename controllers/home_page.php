<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();


require_once '../back/carpool/home_page.php';

$travelsSearched = $_SESSION['travelsSearched'] ?? [];
$nextTravelDate = $_SESSION['nextTravelDate'] ?? [];
$error_message = $_SESSION['error_message'] ?? null;
// Delete session variables after recovery
unset($_SESSION['travelsSearched'], $_SESSION['nextTravelDate'], $_SESSION['error_message']);

$pageTitle = "Accueil";
$customScript =  "carpool_search.js" ;
$templatePage = "pages/home_page.php";

include "../templates/layout.php";

?>
<style>
    #home-page {
        color: #F2C674;
    }
</style>