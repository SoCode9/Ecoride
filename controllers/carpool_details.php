<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once '../back/carpool/search.php';
require_once '../back/carpool/detail.php';

$pageTitle = "Détail du covoiturage";
$customScript = "carpool_details.js";
$templatePage = "pages/carpool_details.php";

include __DIR__ . "/../templates/layout.php";

?>
<style>
    #carpool-button {
        color: var(--col-orange);
    }
</style>