<?php
session_start();


require_once "../database.php";
require_once "../class/User.php";

//CONNECTION WITH DATABASE WHEN AN ACCOUNT IS CREATE

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pseudo = $_POST['pseudo'];
        $mail = $_POST['mail'];
        $password = $_POST['password'];

        // User creation
        $newUser = new User($pdo, null, $pseudo, $mail, $password, $chauffeur);

        // Check if user is created well
        if ($newUser->saveUserToDatabase()) {
            $_SESSION['success_message'] = 'Compte créé avec succès ! Vous avez été crédité de 20 crédits 🎉';
            $_SESSION['user_id'] = $newUser->getId();
            header('Location: carpoolSearchIndex.php');
            exit();
        } else {
            throw new Exception("Erreur lors de l'enregistrement en base de données.");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: loginPageIndex.php'); // Redirect to login page
        exit();
    }
}


?>