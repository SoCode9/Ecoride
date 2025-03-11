<?php
require_once "../back/loginPageBack.php";
?>

<?php include '../templates/header.php'; ?>
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
    <?php include '../templates/loginPage.php'; //PENSER à CHANGER L'ID PSEUDO ETC AVEC CREATEpSEUDO CAR AURA BESOIN DE ID POUR FORMULAIRE DE CONNEXION
    ?>
    <?php include '../templates/footer.php'; ?>
    <!--MANQUE FORM LOGIN-->

</body>

</html>