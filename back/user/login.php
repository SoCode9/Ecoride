<?php
session_start();

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
        if ($newUser->saveUserToDatabase(1)) {
            $_SESSION['success_message'] = 'Compte créé avec succès ! Vous avez été crédité de 20 crédits 🎉';
            $_SESSION['user_id'] = $newUser->getId();
            $_SESSION['role_user'] = $newUser->getIdRole();
            header('Location: carpool_search.php'); //METTRE LA PAGE D?ACCEUIL QUAND PRETTE
            exit();
        } else {
            throw new Exception("Erreur lors de l'enregistrement en base de données.");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: login.php'); // Redirect to login page
        exit();
    }
    //CONNECTION WITH DATABASE WHEN THE USER TRY TO CONNECT
} elseif (($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === "formLogin")) {
    try {
        $mail = $_POST['mail'];
        $password = $_POST['password'];

        $searchUser = new User($pdo, null, null, $mail, $password);

        if ($searchUser->searchUserInDB($mail, $password)) {
            $_SESSION['success_message'] = 'Connexion réussie !';
            $_SESSION['user_id'] = $searchUser->getId();
            $_SESSION['role_user'] = $searchUser->getIdRole();
            header('Location: carpool_search.php'); ///METTRE LA PAGE D?ACCEUIL QUAND PRETTE
            exit();
        } else {
            throw new Exception("Erreur lors de la connexion de l'utilisateur");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: login.php'); // Redirect to login page
        exit();
    }
}