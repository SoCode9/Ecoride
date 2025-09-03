<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

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