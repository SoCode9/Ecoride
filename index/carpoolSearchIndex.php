<?php

require_once '../back/carpoolSearchBack.php';

?>

<?php include '../templates/header.php'; ?>
<html>
<style>
    /*Adapt Login button*/
    #carpoolButton {
        color: #F2C674;
    }

    /*?? add a hover on login button ??*/
</style>

</html>
<?php

//####A ENLEVER, POUR TESTER L'AFFICHAGE DES TRAJETS DE LA RECHERCHE. FAUDRA LE METTRE SOUS "DéPART LE"
/* $travel = new Travel($pdo);
$travel->searchTravels('2025-03-21', 'Saint-Julien', 'Lyon'); */
?>
<div>
    <?php include '../templates/carpoolSearch.php';
    ?>

</div>