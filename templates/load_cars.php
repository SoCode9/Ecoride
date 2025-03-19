<?php
require_once "../database.php";
require_once "../class/Car.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$idUser) {
    die("❌ Utilisateur non connecté.");
}

$carsInDB = new Car($pdo, $idUser, null);

// Verify if cars exist
if (empty($carsInDB->cars)) {
    echo "Ajouter au moins une voiture";
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
        <span>Electrique ? : <?php
        $electric = (htmlspecialchars($car['car_electric']) === 1) ? 'Oui' : 'Non';
        echo $electric;
        ?>
        </span>
        <span>Couleur : <?= htmlspecialchars($car['car_color']) ?></span>
        <span>Nombre de passagers possible : <?= htmlspecialchars($car['car_seats_offered']) ?></span>
        <?php if ($index !== $totalCars):
            echo '<hr>' ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>