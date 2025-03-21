<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";

$idCarToDelete = $_GET['id'];

$idDriver = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $sql = 'DELETE FROM cars WHERE car_id = :carId';
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':carId', $idCarToDelete, PDO::PARAM_INT);
    $statement->execute();
    header('Location:../index/userSpaceIndex.php');
} else {
    echo "Erreur lors de la suppression de la voiture.$idCarToDelete";
}