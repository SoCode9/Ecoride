<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../database.php";

require_once "../class/Rating.php";
require_once "../class/Driver.php";
require_once "../class/Reservation.php";

$employee = new User($pdo, $_SESSION['user_id']);

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$ratingsPerPage = 5;
$offset = ($page - 1) * $ratingsPerPage;

$rating = new Rating($pdo);
$ratingsInValidation = $rating->loadRatingsInValidation($ratingsPerPage, $offset);

// pagination
$totalRatings = $rating->countAllRatingsInValidation();
$totalPages = ceil($totalRatings / $ratingsPerPage);

if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])) {
    $idRating = $_GET['id'];
    if ($_GET['action'] == 'validate_rating') {
        $rating->validateRating($pdo, $idRating, 'validated');

        $_SESSION['success_message'] = "Avis validé";
    } elseif ($_GET['action'] == 'reject_rating') {
        $rating->validateRating($pdo, $idRating, 'refused');

        $_SESSION['success_message'] = "Avis rejeté";
    }
    header('Location: ../index/employeeSpaceIndex.php');
}

$reservation = new Reservation($pdo);

$pageBadComments = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$badCommentsPerPage = 5;
$offsetBadComments = ($pageBadComments - 1) * $badCommentsPerPage;
$badComments = $reservation->getBadComments($badCommentsPerPage, $offsetBadComments);

$totalBadComments = $reservation->countAllBadComments();
$totalPagesBadComments = ceil($totalBadComments / $badCommentsPerPage);
