<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once "../back/user/admin_space.php";

$pageTitle = "Espace Administrateur";
$customScript = "admin_space.js";
$templatePage = "pages/admin_space.php";


include __DIR__ . "/../templates/layout.php";
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= BASE_URL ?>/script/charts.js" defer></script>
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