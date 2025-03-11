<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/headerFooter.css">
    <link rel="stylesheet" href="../css/carpoolSearch.css">
    <link rel="stylesheet" href="../css/carpoolDetails.css">
    <link rel="stylesheet" href="../css/loginPage.css">
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
                <a class="boutonNav" id="loginButton" href="loginPageIndex.php">Connexion</a>
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
    document.addEventListener("DOMContentLoaded", function() {
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