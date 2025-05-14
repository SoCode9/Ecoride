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
    #admin-space {
        background-color: var(--col-orange);
        color: black;
        border: var(--col-orange);
    }

    canvas {
        width: 400px;
        height: 200px;
        display: block;
        margin: 0 auto
    }
</style>