<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../back/user/employee_space.php";

$pageTitle = "Espace Employé";
$customScript = "employee_space.js";
$templatePage = "pages/employee_space.php";

include __DIR__ . "/../templates/layout.php";
?>

<style>
    #employee-space {
        background-color: var(--col-orange);
        color: black;
        border: var(--col-orange);
    }
</style>