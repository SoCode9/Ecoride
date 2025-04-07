<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../class/User.php";

$administrator = new User($pdo, $_SESSION['user_id']);

$employeeList = $administrator->loadListUsersFromDB(4);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'suspend-employee') {
        $idUser = $_GET['id'];
        $administrator->setIsActivatedUser($idUser, 0);
        header('Location: ../index/adminSpaceIndex.php');
        $_SESSION['success_message'] = "L'employé a bien été désactivé";

    } elseif ($_GET['action'] === 'reactivate-employee') {
        $idUser = $_GET['id'];
        $administrator->setIsActivatedUser($idUser, 1);
        header('Location: ../index/adminSpaceIndex.php');
        $_SESSION['success_message'] = "L'employé a été réactivé avec succès";
    }
}