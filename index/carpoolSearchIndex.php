<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/* session_start();
 */
require_once '../back/carpoolSearchBack.php';

$travelsSearched = $_SESSION['travelsSearched'] ?? [];
$nextTravelDate = $_SESSION['nextTravelDate'] ?? [];
$error_message = $_SESSION['error_message'] ?? null;
// Supprimer les variables de session après récupération
unset($_SESSION['travelsSearched'], $_SESSION['nextTravelDate'], $_SESSION['error_message']);



?>

<?php include '../templates/header.php'; ?>
<html>
<style>
    /*Adapt Login button*/
    #carpoolButton {
        color: #F2C674;
    }

    /*?? add a hover on login button ??*/
</style>


<head>

    <title>Covoiturages</title>
    <script src="../css/carpoolSearch.js" defer></script>

</head>

<body>

    <?php include '../templates/carpoolSearch.php'; ?>
    <?php include '../templates/footer.php'; ?>

</body>

</html>