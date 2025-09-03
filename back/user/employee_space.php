<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/Driver.php";
require_once __DIR__ . "/../../class/Rating.php";
require_once __DIR__ . "/../../class/Reservation.php";

$pdo = pdo();

try {
    $employee = User::fromId($pdo, $_SESSION['user_id']);

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
} catch (Exception $e) {
    error_log("Error loading employee_space : " . $e->getMessage());
    $_SESSION['error_message'] = "Une erreur est survenue";
    header('Location: ../../controllers/employee_space.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])) {
    try {
        if ($_GET['action'] == 'validate_rating') {
            $idRating = $_GET['id'];
            $rating->validateRating($idRating, 'validated');

            $_SESSION['success_message'] = "Avis validé";
            header('Location: ../../controllers/employee_space.php');
        } elseif ($_GET['action'] == 'reject_rating') {
            $idRating = $_GET['id'];
            $rating->validateRating($idRating, 'refused');

            $_SESSION['success_message'] = "Avis rejeté";
            header('Location: ../../controllers/employee_space.php');
        } elseif ($_GET['action'] == 'resolved') {
            $reservationId = $_GET['id'];
            $reservation->resolveBadComment($reservationId);

            $_SESSION['success_message'] = "Litige résolu";
            header('Location: ../../controllers/employee_space.php?tab=bad-carpool');
        }
        exit;
    } catch (Exception $e) {
        error_log("Employee action error : " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue";
        header('Location: ../../controllers/employee_space.php');
        exit;
    }
}
