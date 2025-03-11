<?php
//CONNECTION TO THE DATABASE

$host = "localhost";
$dbname = "ecoride";
$port = 3308;
$username = "root"; // Modifier selon ton environnement
$password = ""; // Modifier si besoin

try {
    //création de l'objet PDI pour la connexion à MySql
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    //configuration des erreurs PDO en mode Exception (utile pour le débogage). Active le mode exception pour afficher les erreurs SQL.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { // Capture les erreurs et affiche un message.
    //gestion des erreurs de connexion
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}






//A METTRE LORS DE LA CONNEXION
   // $_SESSION['user_id'] = (int) 3 /* null */; //remplacer par $user['id'] quand ok
//$_SESSION['user_pseudo'] = $user['pseudo'];
//$_SESSION['user_credit'] = 10; // Ajouter des crédits pour le test



?>