<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";
require_once "../class/Car.php";

$pdo = isset($pdo) ? $pdo : null;
$idUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === "formCar")) {
    header('Content-Type: application/json');

    $licencePlate = $_POST['licence_plate'];
    $firstRegistrationDate = $_POST['first_registration_date'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $electric = $_POST['electric'];
    $color = $_POST['color'];
    $seatOffered = $_POST['nb_passengers'];
    try {
        $newCar = new Car($pdo, $idUser, null);
        $newCar->createCar($pdo, $idUser, $brand, $model, $licencePlate, $firstRegistrationDate, $seatOffered, $electric, $color);

        echo json_encode([
            "success" => true,
            "brand" => $brand,
            "model" => $model,
            "licence_plate" => $licencePlate
        ]);
    } catch (Exception $e) {
        echo "Exception attrappée : " . $e->getMessage();
    }
    exit();
}