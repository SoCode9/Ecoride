<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../database.php";

require_once "../class/Rating.php";
require_once "../class/Driver.php";
require_once "../class/Reservation.php";

$employee = new User($pdo, $_SESSION['user_id']);

//VERIFY RATINGS TAB
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$ratingsPerPage = 5;
$offset = ($page - 1) * $ratingsPerPage;

$rating = new Rating($pdo);
$ratingsInValidation = $rating->loadRatingsInValidation($ratingsPerPage, $offset);

// pagination
$totalRatings = $rating->countAllRatingsInValidation();
$totalPages = ceil($totalRatings / $ratingsPerPage);


//BAD COMMENTS TAB
$reservation = new Reservation($pdo);

$pageBadComments = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$badCommentsPerPage = 5;
$offsetBadComments = ($pageBadComments - 1) * $badCommentsPerPage;
$badComments = $reservation->getBadComments($badCommentsPerPage, $offsetBadComments);
// pagination
$totalBadComments = $reservation->countAllBadComments();
$totalPagesBadComments = ceil($totalBadComments / $badCommentsPerPage);


if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])) {

    if ($_GET['action'] == 'validate_rating') {
        $idRating = $_GET['id'];
        $rating->validateRating($pdo, $idRating, 'validated');

        $_SESSION['success_message'] = "Avis validé";
        header('Location: ../index/employeeSpaceIndex.php');
    } elseif ($_GET['action'] == 'reject_rating') {
        $idRating = $_GET['id'];
        $rating->validateRating($pdo, $idRating, 'refused');

        $_SESSION['success_message'] = "Avis rejeté";
        header('Location: ../index/employeeSpaceIndex.php');
    } elseif ($_GET['action'] == 'resolved') {
        $reservationId = $_GET['id'];
        $reservation->resolveBadComment($pdo, $reservationId);

        $_SESSION['success_message'] = "Litige résolu";
        header('Location: ../index/employeeSpaceIndex.php?tab=bad-carpool'); 
    }

}
