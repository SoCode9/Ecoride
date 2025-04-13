<?php
require_once "../back/loginPageBack.php";
require_once "../back/userSpaceBack.php";
//require_once "../back/validate_carpool.php";
//require_once "../back/updateUserRoleBack.php";
?>

<?php include '../templates/components/header.php'; ?>
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
    <script src="../script/user_space.js" defer></script>

</head>

<body>
    <?php include '../templates/pages/user_space.php';
    ?>
    <?php include '../templates/components/footer.php'; ?>

</body>

</html>