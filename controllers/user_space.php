<?php

require_once "../back/user/login.php";
require_once "../back/user/user_space.php";

$pageTitle = "Espace utilisateur";
$customScript = "user_space.js";
$templatePage = "pages/user_space.php";

include __DIR__ . "/../templates/layout.php";

?>

<style>
    #user-space {
        background-color: var(--col-orange);
        color: black;
        border: var(--col-orange);
    }
</style>