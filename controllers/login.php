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
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }

    /*?? add a hover on login button ??*/
</style>