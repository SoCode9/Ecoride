<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

$pageTitle = "Mentions légales";
$customScript = null;
$templatePage = "pages/legal_informations.php";

include __DIR__ . "/../templates/layout.php";
?>

<style>
    /* #employee-space {
        background-color: var(--col-orange);
        color: black;
        border: var(--col-orange);
    } */
</style>