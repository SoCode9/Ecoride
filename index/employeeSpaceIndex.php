<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../database.php";
require_once "../back/employeeSpaceBack.php";

?>

<?php include "../templates/header.php"; ?>

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
    <script src="../script/employeeSpace.js" defer></script>

</head>

<body>
    <?php
    include "../templates/employeeSpace.php";
    include "../templates/footer.php";
    ?>
</body>

</html>