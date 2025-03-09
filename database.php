<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//CONNECTION TO THE DATABASE

$host = "localhost";
$dbname = "ecoride";
$port = 3307;
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
    $_SESSION['user_id'] = (int) 3; //remplacer par $user['id'] quand ok
//$_SESSION['user_pseudo'] = $user['pseudo'];


?>