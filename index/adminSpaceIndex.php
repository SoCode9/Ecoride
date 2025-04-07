<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";

?>
<html>


<head>
    <title>Espace Administrateur</title>

    <style>
        #adminSpace {
            background-color: #F2C674;
            color: black;
            border: #F2C674;
        }
    </style>
</head>

<body>

    <?php
    include "../templates/header.php";
    include "../templates/adminSpace.php";

    include "../templates/footer.php";
    ?>

</body>

</html>