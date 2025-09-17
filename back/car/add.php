<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
requireDriver(); // Checks whether the user is a driver
require_once __DIR__ . "/../../init.php";
require_once __DIR__ . "/../../class/Car.php";

$pdo = MysqlConnection::getPdo();

$idUser = $_SESSION['user_id'];

if (($_SERVER['REQUEST_METHOD'] === "POST")) {
    header('Content-Type: application/json');
    try {
        $licencePlate = $_POST['licence_plate'];
        $firstRegistrationDate = $_POST['first_registration_date'];
        $brand = $_POST['brand'];
        $model = $_POST['model'];
        $electricValue = $_POST['electric'];
        if ($electricValue === "yes") {
            $electric = 1;
        } elseif ($electricValue === "no") {
            $electric = 0;
        }
        $color = $_POST['color'];
        $seatOffered = $_POST['nb_passengers'];

        $newCar = new Car($pdo, $idUser, null);
        $newCar->createNewCar($idUser, $brand, $model, $licencePlate, $firstRegistrationDate, $seatOffered, $electric, $color);

        echo json_encode([
            "success" => true,
            "brand" => $brand,
            "model" => $model,
            "licence_plate" => $licencePlate
        ]);
    } catch (Exception $e) {
        error_log("Erreur dans car/add.php : " . $e->getMessage());
        echo json_encode([
            "success" => false,
            "message" => "Une erreur est survenue lors de l'ajout du véhicule"
        ]);
    }
    exit();
}
