<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();


//require_once '../back/carpool/search.php';

$pageTitle = "Contact";
$customScript = null;
$templatePage = "pages/contact.php";

include __DIR__ . "/../templates/layout.php";

?>
<style>
    #contact-button {
        color: var(--col-orange);
    }
</style>
