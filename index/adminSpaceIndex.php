<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";
require_once "../back/adminSpaceBack.php";
?>
<html>


<head>
    <title>Espace Administrateur</title>
    <script src="../script/admin_space.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../script/chart_carpools_per_day.js" defer></script>
    <style>
        #adminSpace {
            background-color: #F2C674;
            color: black;
            border: #F2C674;
        }

        canvas {
            width: 400px;
            height: 200px;
            display: block;
            margin: 0 auto
        }

    </style>
</head>

<body>

    <?php
    include "../templates/components/header.php";
    include "../templates/pages/admin_space.php";

    include "../templates/components/footer.php";
    ?>

</body>

</html>