<?php
require_once "../database.php";
require_once "../class/User.php";

//CONNECTION WITH DATABASE WHEN AN ACCOUNT IS CREATE

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pseudo = $_POST['pseudo'];
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $chauffeur = isset($_POST['chauffeur']) ? 1 : 0;

        // Création de l'utilisateur
        $newUser = new User($pdo, null, $pseudo, $mail, $password, $chauffeur);

        // Vérifie si l'utilisateur a bien été enregistré en base
        if ($newUser->saveUserToDatabase()) {
            $_SESSION['success_message'] = 'Compte créé avec succès ! Vous avez été crédité de 20 crédits 🎉';

            // Redirige vers la page de recherche de covoiturage
            header('Location: carpoolSearchIndex.php');
            exit();
        } else {
            throw new Exception("Erreur lors de l'enregistrement en base de données.");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: loginPageIndex.php'); // Redirection vers la page de connexion
        exit();
    }
}


?>