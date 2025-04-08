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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create-employee') {
    try {
        $pseudoEmployee = $_POST['pseudo_employee'];
        $mailEmployee = $_POST['mail_employee'];
        $passwordEmployee = $_POST['password_employee'];

        $newEmployee = new User($pdo, null, $pseudoEmployee, $mailEmployee, $passwordEmployee);
        $newEmployee->saveUserToDatabase(4);
        header('Location:../index/adminSpaceIndex.php');
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: ../index/adminSpaceIndex.php'); 
        exit();
    }

}