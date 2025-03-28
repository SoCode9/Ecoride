<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../back/createCarpoolBack.php';


?>

<?php include '../templates/header.php'; ?>
<html>
<style>
    #userSpace {
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }
</style>



<head>

    <title>Proposer un covoiturage</title>
    <script src="../script/carpoolSearch.js" defer></script>

</head>


<body>

    <?php include '../templates/createCarpool.php'; ?>
    <?php include '../templates/footer.php'; ?>

</body>

</html>