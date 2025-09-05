<?php

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../class/Travel.php";

$pdo = pdo();

$administrator = User::fromId($pdo, $_SESSION['user_id']);

try {
    //display the users on the tabs
    $employeeList = $administrator->loadListUsersFromDB(4);
    $passengersList = $administrator->loadListUsersFromDB(1);
    $driversList = $administrator->loadListUsersFromDB(2);
    $passengersAndDriversList = $administrator->loadListUsersFromDB(3);
} catch (Exception $e) {
    error_log("Error in load users : " . $e->getMessage());
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: ../../controllers/admin_space.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    //activated and desactivated an employee
    if ($_GET['action'] === 'suspend-employee') {
        $idUser = $_GET['id'];
        try {
            $administrator->setIsActivatedUser($_GET['id'], 0);
            $_SESSION['success_message'] = "L'employé a bien été désactivé";
        } catch (Exception $e) {
            error_log("Error suspend-employee : " . $e->getMessage());
            $_SESSION['error_message'] = "Impossible de désactiver l'employé";
        }
        header('Location: ../../controllers/admin_space.php?tab=employees-management');
        exit();
    } elseif ($_GET['action'] === 'reactivate-employee') {
        $idUser = $_GET['id'];
        try {
            $administrator->setIsActivatedUser($idUser, 1);
            $_SESSION['success_message'] = "L'employé a été réactivé avec succès";
        } catch (Exception $e) {
            error_log("Error reactivate-employee (ID: $idUser) : " . $e->getMessage());
            $_SESSION['error_message'] = "Impossible de réactiver l'employé";
        }
        header('Location: ../../controllers/admin_space.php?tab=employees-management');
        exit();

        //activated and desactivated a user
    } elseif ($_GET['action'] === 'suspend-user') {
        $idUser = $_GET['id'];
        try {
            $administrator->setIsActivatedUser($idUser, 0);
            $_SESSION['success_message'] = "L'utilisateur a bien été désactivé";
        } catch (Exception $e) {
            error_log("Error suspend-user (ID: $idUser) : " . $e->getMessage());
            $_SESSION['error_message'] = "Impossible de désactiver l'utilisateur";
        }
        header('Location: ../../controllers/admin_space.php?tab=users-management');
        exit();
    } elseif ($_GET['action'] === 'reactivate-user') {
        $idUser = $_GET['id'];
        try {
            $administrator->setIsActivatedUser($idUser, 1);
            $_SESSION['success_message'] = "L'utilisateur a été réactivé avec succès";
        } catch (Exception $e) {
            error_log("Error reactivate-user (ID: $idUser) : " . $e->getMessage());
            $_SESSION['error_message'] = "Impossible de réactiver l'utilisateur";
        }
        header('Location: ../../controllers/admin_space.php?tab=users-management');
        exit();
    }
}

//create an employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create-employee') {
    try {
        $pseudoEmployee = $_POST['pseudo-employee'];
        $mailEmployee = $_POST['mail-employee'];
        $passwordEmployee = $_POST['password-employee'];

        $newEmployee = User::register($pdo, $pseudoEmployee, $mailEmployee, $passwordEmployee, 4);
        $_SESSION['success_message'] = "Compte employé créé avec succès";
        header('Location:../../controllers/admin_space.php');
        exit;
    } catch (Exception $e) {
        error_log("Error in employee creation : " . $e->getMessage());
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: ../../controllers/admin_space.php');
        exit();
    }
}
