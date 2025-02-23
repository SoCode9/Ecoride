<?php
require_once "database.php";
require_once "../class/User.php";

//CONNECTION WITH DATABASE WHEN AN ACCOUNT IS CREATE

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pseudo = $_POST["pseudo"];
    $mail = $_POST["mail"];
    $password = $_POST["password"];
    $chauffeur = isset($_POST["chauffeur"]) ? true : false; //si checkbox cochée-> renvoi true, sinon false

    $nouvelUser = new User($pseudo, $mail, $password, $chauffeur);

    if ($nouvelUser->saveUserToDatabase()) {
        echo 'Compte créé avec succès';
        //ou ajoute ici une redirection sur autre page
    } else {
        echo 'Erreur lors de la création du compte';
    }
    //print_r($nouvelUser->infoUserInArray()); //pour tester
}

echo getDate();
?>