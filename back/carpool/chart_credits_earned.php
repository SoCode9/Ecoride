<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
requireAdmin(); // Checks whether the user is an administrator
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../functions.php";

$pdo = pdo();

try {
    $sql = 'SELECT validated_at AS validationCarpoolDate, count(validated_at)*2 AS carpoolsValidated FROM travels WHERE validated_at IS NOT NULL GROUP BY validationCarpoolDate ORDER BY validationCarpoolDate ASC LIMIT 10';
    $statement = $pdo->prepare($sql);

    $statement->execute();

    $data = [];

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $row['validationCarpoolDate'] = formatDateWeekday($row['validationCarpoolDate']);
        array_push($data, $row);
    }

    echo json_encode($data);
} catch (PDOException $e) {
    error_log("Database error in chart_credits_earned.php : " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Une erreur est survenue lors du chargement des données"
    ]);
}
