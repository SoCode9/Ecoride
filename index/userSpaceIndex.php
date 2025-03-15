<?php
require_once "../back/loginPageBack.php";
require_once "../back/userSpaceBack.php";
?>

<?php include '../templates/header.php'; ?>
<html>
<style>
    /*Adapt Login button*/
    #userSpace {
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }

    /*?? add a hover on login button ??*/
</style>

<head>

    <title>Espace utilisateur</title>

</head>

<body>
    <?php include '../templates/userSpace.php'; //PENSER à CHANGER L'ID PSEUDO ETC AVEC CREATEpSEUDO CAR AURA BESOIN DE ID POUR FORMULAIRE DE CONNEXION
    ?>
    <?php include '../templates/footer.php'; ?>
    <!--MANQUE FORM LOGIN-->

</body>

</html>