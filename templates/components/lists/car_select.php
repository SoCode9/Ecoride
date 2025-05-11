<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../../database.php";
require_once __DIR__ . "/../../../class/Car.php";

$driverId = $_SESSION['user_id'];
$cars = new Car($pdo, $driverId);
$carsOfDriver = $cars->getCars();

?>

<label for="carSelected">Voiture</label>
<select id="car-selected" name="carSelected" required>
    <?php foreach ($carsOfDriver as $car): ?>
        <option value="<?= htmlspecialchars($car['car_id']) ?>">
            <?= htmlspecialchars($car['name'] . " " . $car['car_model']) ?>
        </option>
    <?php endforeach; ?>
</select>