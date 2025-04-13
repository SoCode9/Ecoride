<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../back/createCarpoolBack.php';


?>

<?php include '../templates/components/header.php'; ?>
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
    <script src="../script/carpool_search.js" defer></script>

</head>


<body>

    <?php include '../templates/pages/create_carpool.php'; ?>
    <?php include '../templates/components/footer.php'; ?>

</body>

</html>