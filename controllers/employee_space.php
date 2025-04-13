<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../back/employeeSpaceBack.php";

$pageTitle = "Espace Employé";
$customScript = "employee_space.js";
$templatePage = "pages/employee_space.php";

include "../templates/layout.php";
?>

<style>
    #employeeSpace {
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }
</style>