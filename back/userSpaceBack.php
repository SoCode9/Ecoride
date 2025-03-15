<?php

require_once "../database.php";
require_once "../class/User.php";
require_once "../class/Driver.php";

$idUser = $_SESSION['user_id'];

$connectedUser = new User($pdo, $idUser, null, null, null);
if (($connectedUser->getIdRole() === 2) or ($connectedUser->getIdRole() === 3)) {
    $connectedDriver = new Driver($pdo, $connectedUser->getId());
}
