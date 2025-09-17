<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
requireAdmin(); // Checks whether the user is an administrator
require_once __DIR__ . "/../../init.php";
require_once __DIR__ . "/../../functions.php";

$pdo = MysqlConnection::getPdo();

try {
    $today = date('Y-m-d');

    $sql = 'SELECT travel_date AS travelDate, count(id) AS nbCarpool FROM travels WHERE (travel_status <> "cancelled") AND travel_date >= :today GROUP BY travel_date ORDER BY travel_date ASC LIMIT 10';
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':today', $today, PDO::PARAM_STR);

    $statement->execute();

    $data = [];

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $row['travelDate'] = formatDateWeekday($row['travelDate']);
        array_push($data, $row);
    }

    echo json_encode($data);
} catch (PDOException $e) {
    error_log("Database error in chart_per_day.php : " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Une erreur est survenue lors du chargement des données."
    ]);
}
