<?php
require_once "back/formTravelBack.php";
require_once "commom.php";
?>

<?php include 'templates/header.php'; ?>
<html>
<style>
    /*Adapt header*/
</style>


<div>
    <?php include 'templates/formTravel.php';
    ?>

</div>

<h1>Liste des trajets enregistrés</h1>

<?php
$travel = new Travel($pdo);
//$travel->displayTravelsBrut("SELECT * FROM travels ORDER BY travel_date DESC", "travel_date");
//$travel->allFuturesTravels();
//$travel->searchTravels('2025-03-21', 'Saint-Julien', 'Lyon');
?>

</html>