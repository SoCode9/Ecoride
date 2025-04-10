<?php
require_once "../database.php";
require_once "../class/Car.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$carsInDB = new Car($pdo, $idUser, null);

// Verify if cars exist
if (empty($carsInDB->cars)) {
    echo "<span style='color:red; font-style:italic;'>Ajouter au moins une voiture</span>";
}

$cars = $carsInDB->cars;

if (isset($cars)):
    $totalCars = count($cars);

    $index = 0;
    foreach ($cars as $car):
        $index++;
        ?>
        <span>Plaque immatriculation : <?= htmlspecialchars($car['car_licence_plate']) ?></span>
        <span>Date première immatriculation :
            <?= formatDate(htmlspecialchars($car['car_first_registration_date'])) ?></span>
        <span>Marque : <?= htmlspecialchars($car['name']) ?></span>
        <span>Modèle : <?= htmlspecialchars($car['car_model']) ?></span>
        <span>Electrique : <?php
        $electric = (htmlspecialchars($car['car_electric']) == 1) ? 'Oui' : 'Non';
        echo $electric;
        ?>
        </span>
        <span>Couleur : <?= htmlspecialchars($car['car_color']) ?></span>
        <span>Nombre de passagers possible : <?= htmlspecialchars($car['car_seats_offered']) ?></span>
        <a href="../back/delete_car.php?action=delete_car&id=<?=$car['car_id']?>"><img src="../icons/Supprimer.png" class="imgFilter"
                style="cursor: pointer;"></a>
        <?php if ($index !== $totalCars):
            echo '<hr>' ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>