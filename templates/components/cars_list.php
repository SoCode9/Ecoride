<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Car.php";

$idUser = $_SESSION['user_id'];

$car = new Car($pdo, $idUser, null);
$cars = $car->cars;

if (empty($cars)): ?>
    <span style='color:red; font-style:italic;'>Ajouter au moins une voiture</span>
<?php else: ?>
    <?php $totalCars = count($cars); ?>
    <?php foreach ($cars as $index => $car): ?>
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
        <a href="<?= BASE_URL ?>/back/car/delete.php?action=delete_car&id=<?= $car['car_id'] ?>">
            <img src="../icons/Supprimer.png" class="img-width-20" style="cursor: pointer;">
        </a>
        <?php if ($index !== $totalCars - 1):
            echo '<hr>';
        endif; ?>
    <?php endforeach; ?>
<?php endif; ?>