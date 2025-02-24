<?php

require_once '../database.php';
require_once "../class/Travel.php";

?>

<?php

$travelInstance = new Travel($pdo);


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $travelId = intval($_GET['id']);
} else {
    die("ID de trajet invalide.");
}

$sql = "SELECT travels.*, users.*, driver.*, cars.*, brand.* FROM travels 
JOIN users ON users.id = travels.driver_id JOIN driver ON driver.user_id = travels.driver_id JOIN cars ON cars.car_id = travels.car_id JOIN brand ON brand.id = cars.brand_id
WHERE travels.id LIKE :travel_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":travel_id", $travelId, PDO::PARAM_INT);
$statement->execute();
$travel = $statement->fetch(PDO::FETCH_ASSOC);

if (!$travel) {
    die("Ce trajet n'existe pas.");
}