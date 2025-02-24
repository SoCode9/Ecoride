<?php

require_once '../database.php';
require_once "../class/Travel.php";

?>

<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $travelId = intval($_GET['id']);
} else {
    die("ID de trajet invalide.");
}

$sql = "SELECT * FROM travels WHERE id LIKE :travel_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":travel_id", $travelId, PDO::PARAM_INT);
$statement->execute();
$travel = $statement->fetch(PDO::FETCH_ASSOC);

if (!$travel) {
    die("Ce trajet n'existe pas.");
}