<?php

require_once "../back/user/login.php";
require_once "../back/user/user_space.php";

$pageTitle = "Espace utilisateur";
$customScript = "user_space.js";
$templatePage = "pages/user_space.php";

include "../templates/layout.php";

?>

<style>
    #userSpace {
        background-color: #F2C674;
        color: black;
        border: #F2C674;
    }
</style>