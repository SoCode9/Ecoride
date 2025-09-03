<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

$pageTitle = "Mentions légales";
$customScript = null;
$templatePage = "pages/legal_informations.php";

include __DIR__ . "/../templates/layout.php";
?>

<style>

</style>