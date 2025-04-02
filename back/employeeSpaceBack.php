<?php

require_once "../database.php";

require_once "../class/Rating.php";
require_once "../class/Driver.php";


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$ratingsPerPage = 5;
$offset = ($page - 1) * $ratingsPerPage;

$rating = new Rating($pdo);
$ratingsInValidation = $rating->loadRatingsInValidation($ratingsPerPage, $offset);

// pour la pagination : récupérer le nombre total
$totalRatings = $rating->countAllRatingsInValidation();
$totalPages = ceil($totalRatings / $ratingsPerPage);