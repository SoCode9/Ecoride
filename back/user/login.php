<?php
session_start();
require_once __DIR__ . "/../../functions.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/User.php";

//CONNECTION WITH DATABASE WHEN AN ACCOUNT IS CREATE
if (($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === "createAccount")) {

    try {
        $pseudo = $_POST['pseudo'];
        $mail = $_POST['mail'];
        $password = $_POST['password'];

        // User creation
        $newUser = new User($pdo, null, $pseudo, $mail, $password);

        // Check if user is created well
        $newUser->saveUserToDatabase(1);
        $_SESSION['success_message'] = 'Compte créé avec succès ! Vous avez été crédité de 20 crédits 🎉';
        $_SESSION['user_id'] = $newUser->getId();
        $_SESSION['role_user'] = $newUser->getIdRole();
        header('Location: ' . BASE_URL . '/index.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: login.php');
        exit();
    }
    //CONNECTION WITH DATABASE WHEN THE USER TRY TO CONNECT
} elseif (($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === "formLogin")) {
    try {
        $mail = $_POST['mail'];
        $password = $_POST['password'];

        $searchUser = new User($pdo, null, null, $mail, $password);

        $searchUser->searchUserInDB($mail, $password);
        $_SESSION['success_message'] = 'Connexion réussie !';
        $_SESSION['user_id'] = $searchUser->getId();
        $_SESSION['role_user'] = $searchUser->getIdRole();
        header('Location: login.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: login.php');
        exit();
    }
}