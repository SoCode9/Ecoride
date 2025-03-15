<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../back/carpoolSearchBack.php';
require_once '../back/carpoolDetailsBack.php';

?>

<?php include '../templates/header.php'; ?>
<html>
<style>
    /*Adapt Login button*/
    #carpoolButton {
        color: #F2C674;
    }

    /*?? add a hover on login button ??*/
</style>



<head>

    <title>Détail du covoiturage</title>
    <script src="../script/carpoolDetails.js" defer></script>

</head>


<body>

    <?php include '../templates/carpoolDetails.php'; ?>
    <?php include '../templates/footer.php'; ?>

</body>

</html>