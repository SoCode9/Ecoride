<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../database.php";
require_once "../back/employeeSpaceBack.php";

?>

<?php include "../templates/components/header.php"; ?>

<html>

<style>
    #employeeSpace {
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }
</style>

<head>
    <title>Espace Employé </title>
    <script src="../script/employee_space.js" defer></script>

</head>

<body>
    <?php
    include "../templates/pages/employee_space.php";
    include "../templates/components/footer.php";
    ?>
</body>

</html>