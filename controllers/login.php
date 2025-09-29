<?php
require_once __DIR__ . '/../init.php';
require_once "../back/user/login.php";

$pageTitle = "Connexion";
$templatePage = "pages/login.php";

include __DIR__ . "/../templates/layout.php";
?>

<style>
    #login-button {
        background-color: var(--col-orange);
        color: black;
        border: var(--col-orange);
    }
</style>