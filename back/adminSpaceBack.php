<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../class/User.php";
require_once "../class/Travel.php";

$administrator = new User($pdo, $_SESSION['user_id']);

//display the users on the tabs
$employeeList = $administrator->loadListUsersFromDB(4);
$passengersList = $administrator->loadListUsersFromDB(1);
$driversList = $administrator->loadListUsersFromDB(2);
$passengersAndDriversList = $administrator->loadListUsersFromDB(3);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    //activated and desactivated an employee
    if ($_GET['action'] === 'suspend-employee') {
        $idUser = $_GET['id'];
        $administrator->setIsActivatedUser($idUser, 0);
        header('Location: ../controllers/admin_space.php?tab=employees-management');
        $_SESSION['success_message'] = "L'employé a bien été désactivé";

    } elseif ($_GET['action'] === 'reactivate-employee') {
        $idUser = $_GET['id'];
        $administrator->setIsActivatedUser($idUser, 1);
        header('Location: ../controllers/admin_space.php?tab=employees-management');
        $_SESSION['success_message'] = "L'employé a été réactivé avec succès";

    //activated and desactivated a user
    } elseif ($_GET['action'] === 'suspend-user') {
        $idUser = $_GET['id'];
        $administrator->setIsActivatedUser($idUser, 0);
        header('Location: ../controllers/admin_space.php?tab=users-management');
        $_SESSION['success_message'] = "L'utilisateur  a bien été désactivé";

    } elseif ($_GET['action'] === 'reactivate-user') {
        $idUser = $_GET['id'];
        $administrator->setIsActivatedUser($idUser, 1);
        header('Location: ../controllers/admin_space.php?tab=users-management');
        $_SESSION['success_message'] = "L'utilisateur a été réactivé avec succès";
    }
}

//create an employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create-employee') {
    try {
        $pseudoEmployee = $_POST['pseudo-employee'];
        $mailEmployee = $_POST['mail-employee'];
        $passwordEmployee = $_POST['password-employee'];

        $newEmployee = new User($pdo, null, $pseudoEmployee, $mailEmployee, $passwordEmployee);
        $newEmployee->saveUserToDatabase(4);
        header('Location:../controllers/admin_space.php');
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: ../controllers/admin_space.php');
        exit();
    }

}