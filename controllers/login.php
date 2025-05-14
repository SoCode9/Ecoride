<?php
require_once "../back/user/login.php";

$pageTitle = "Connexion";
//$customScript = "carpool_details.js";
$templatePage = "pages/login.php";

include __DIR__ . "/../templates/layout.php";
?>

<style>
    /*Adapt Login button*/
    #login-button {
        background-color: var(--col-orange);
        color: black;
        border: var(--col-orange);
    }

    /*?? add a hover on login button ??*/
</style>