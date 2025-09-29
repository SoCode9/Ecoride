<?php
require_once __DIR__ . "/../back/user/auth.php";
require_once __DIR__ . '/../init.php';
require_once '../back/carpool/search.php';

$travelsSearched = $_SESSION['travelsSearched'] ?? [];
$nextTravelDate = $_SESSION['nextTravelDate'] ?? [];
$error_message = $_SESSION['error_message'] ?? null;
// Delete session variables after recovery
unset($_SESSION['travelsSearched'], $_SESSION['nextTravelDate'], $_SESSION['error_message']);

$pageTitle = "Covoiturages";
$customScript = "carpool_search.js";
$templatePage = "pages/carpool_search.php";

include __DIR__ . "/../templates/layout.php";

?>
<style>
    #carpool-button {
        color: var(--col-orange);
    }
</style>