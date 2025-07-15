<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?= BASE_URL ?>/script/login.js" defer></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>
    <header>
        <div class="header">
            <a href="<?= BASE_URL ?>/index.php">
                <img src="<?= BASE_URL ?>/icons/Logo.png" alt="Logo" style=" width: 100px;">
            </a>

            <!--Navigation display for big screens-->
            <div class="navigation">
                <?php renderNavigationLinks(); ?>
            </div>

            <!--Navigation display for small screens-->
            <div id="my-sidenav" class="sidenav" style="display: none;">
                <a id="close-btn" href="#" class="close">×</a>
                <ul>
                    <?php renderNavigationLinks(true); ?>
                </ul>
            </div>

            <div class="current-tab hidden" id="current-tab-mobile"></div>

            <a href="#" id="open-btn" style="display: none;">
                <span class="burger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>
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
        <div class="message" style="background-color: var(--col-light-red); color: var(--col-dark-red);">
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

<script src="<?= BASE_URL ?>/script/burger_menu.js" defer></script>