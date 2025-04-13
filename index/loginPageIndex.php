<?php
require_once "../back/loginPageBack.php";
?>

<?php include '../templates/components/header.php'; ?>
<html>
<style>
    /*Adapt Login button*/
    #loginButton {
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }

    /*?? add a hover on login button ??*/
</style>

<head>

    <title>Connexion</title>

</head>

<body>
    <?php include '../templates/pages/login.php'; 
    ?>
    <?php include '../templates/components/footer.php'; ?>

</body>

</html>