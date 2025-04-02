<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../script/loginPage.js" defer></script>
    <link rel="stylesheet" href="../css/headerFooter.css">
    <link rel="stylesheet" href="../css/carpoolSearch.css">
    <link rel="stylesheet" href="../css/carpoolDetails.css">
    <link rel="stylesheet" href="../css/loginPage.css">
    <link rel="stylesheet" href="../css/userSpace.css">
    <!-- add all the css files-->


</head>

<body>
    <header>
        <div class="header">
            <div>
                <img src="" alt="Logo">
            </div>
            <div class="navigation">
                <a class="boutonNav" href="">Accueil</a> <!--manque-->
                <a class="boutonNav" id="carpoolButton" href="carpoolSearchIndex.php">Covoiturages</a>
                <a class="boutonNav" href="">Contact</a> <!--manque-->
                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['user_id'])) {
                    if ($_SESSION['role_user'] == 1 || $_SESSION['role_user'] == 2 || $_SESSION['role_user'] == 3) { //if the user is a passenger or a driver or both
                        echo '<a class="boutonNav borderButton" id="userSpace" href="userSpaceIndex.php">Espace Utilisateur</a>';
                    } elseif ($_SESSION['role_user'] == 4) { //if the user is an employee
                        echo '<a class="boutonNav borderButton" id="employeeSpace" href="employeeSpaceIndex.php">Espace Employé</a>';
                    }
                    echo '<a id="logoutButton" href="#"> <img src="../icons/Deconnexion.png" alt="logout button"  class="logoutButton"> </a>';
                } else {
                    echo '<a class="boutonNav borderButton" id="loginButton" href="loginPageIndex.php">Connexion</a>';
                }
                ?>
            </div>
        </div>
    </header>

    <!--display of error or success messages-->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Deletes after display
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message" style="background-color: #f8d7da; color: #721c24;">
            <?php
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Deletes after display
            ?>
        </div>
    <?php endif; ?>

</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const message = document.querySelector(".message");
        if (message) {
            setTimeout(() => {
                message.style.opacity = "0"; // Anime the disappearance
                setTimeout(() => {
                    message.style.display = "none"; // Completely hides
                }, 500);
            }, 4000); // 4 seconds before disappearance
        }
    });
</script>