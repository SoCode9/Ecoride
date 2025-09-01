<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

$travelsSearched = $_SESSION['travelsSearched'] ?? [];
$nextTravelDate = $_SESSION['nextTravelDate'] ?? [];
$error_message = $_SESSION['error_message'] ?? null;
// Delete session variables after recovery
unset($_SESSION['travelsSearched'], $_SESSION['nextTravelDate']);

$pageTitle = "Accueil";
$customScript = "carpool_search.js";
$templatePage = "pages/home_page.php";

include __DIR__ . '/templates/layout.php';

?>
<style>
    #home-page {
        color: var(--col-orange);
    }
</style>