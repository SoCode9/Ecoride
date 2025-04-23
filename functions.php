<?php

//CONFIG
define('BASE_URL', '/0-ECFEcoride');


//to store useful functions for all pages

/*---------------- FORMATING ----------------*/

/**
 * To display dates for users (ex. 21/03/2025)
 * @param string $dateToFormat //give the date to format
 * @return string //return the date with the right format (front)
 */
function formatDate(string $dateToFormat): string
{
    $dateToFormat = DateTime::createFromFormat('Y-m-d', $dateToFormat);
    if ($dateToFormat) {
        $formatDate = $dateToFormat->format('d/m/Y');
        return $formatDate;
    } else {
        return 'Erreur sur le format de la date initiale';
    }
}

/**
 * To display dates for users (ex. Vendredi 21 mars 2025)
 * @param string $dateToFormat //give the full date to format
 * @return string //return the date with the upper-case letter of the day
 */
function formatDateLong(string $dateToFormat): string
{
    $dateToFormat = DateTime::createFromFormat('Y-m-d', $dateToFormat);

    if ($dateToFormat) {
        // Initialiser le formatteur de date en français
        $formatter = new IntlDateFormatter(
            'fr_FR', // Langue : français
            IntlDateFormatter::FULL, // Format long : "lundi 12 février 2024"
            IntlDateFormatter::NONE
        );

        $dateFormated = $formatter->format($dateToFormat);
        return ucfirst($dateFormated);
    } else {
        return 'Erreur sur le format de la date initiale';
    }
}

/**
 * To display dates for users (ex. Mars 2025)
 * @param string $dateToFormat //give the full date to format
 * @return string //return the date with the upper-case letter of the day
 */
function formatDateMonthAndYear(string $dateToFormat): string
{
    $dateToFormat = DateTime::createFromFormat('Y-m-d', $dateToFormat);

    if ($dateToFormat) {
        // Initialiser le formatteur de date en français
        $formatter = new IntlDateFormatter(
            'fr_FR', // Langue : français
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            null,
            null,
            'MMMM yyyy'// Format souhaité : Février 2024"
        );

        $dateFormated = $formatter->format($dateToFormat);
        return ucfirst($dateFormated);
    } else {
        return 'Erreur sur le format de la date initiale';
    }
}

/**
 * To display dates for users (ex. Lundi 21/03)
 * @param string $dateToFormat //give the date to format
 * @return string //return the date with the right format (front)
 */
function formatDateWeekday(string $dateToFormat): string
{
    $date = DateTime::createFromFormat('Y-m-d', $dateToFormat);

    if (!$date) {
        return 'Erreur sur le format de la date initiale';
    }

    $formatter = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN,
        'EEEE dd/MM'
    );

    return ucfirst($formatter->format($date));
}

/**
 * To display times for users (ex. 12:00)
 * @param string $timeToFormat //give the time to format
 * @return string //return the time with the right format (front)
 */
function formatTime(string $timeToFormat): string
{
    $timeToFormat = DateTime::createFromFormat('H:i:s', $timeToFormat);
    if ($timeToFormat) {
        $formatTime = $timeToFormat->format("H\hi");
        return $formatTime;
    } else {
        return 'Erreur sur le format de la date initiale';
    }
}



function formatEco(bool $nbEco): string
{
    if ($nbEco == 1) {
        return '<img src="../icons/Arbre 1.png" alt="Arbre" width="20px">' . " Economique";
    }
    return "";
}

function formatEcoSmallScreen(bool $nbEco): string
{
    if ($nbEco == 1) {
        return '<img src="../icons/Arbre 1.png" alt="Arbre" width="20px">' . " Eco";
    }
    return "";
}

/*---------------- CALCULATION ----------------*/

/**
 * calculation of seatsAvailable with informations in DB
 * @param int $seatsOfferedNb //field in DB
 * @param int $seatsAllowedNb //field in DB
 * @return int
 */
function seatsAvailable(int $seatsOfferedNb, int $seatsAllocatedNb): int
{
    $seatsAvailable = $seatsOfferedNb - $seatsAllocatedNb;
    return $seatsAvailable;
}

/*---------------- OTHER DISPLAY ----------------*/

function displayPhoto(?string $fileName = null): string
{
    $real_path = __DIR__ . '/photos/' . $fileName;

    if (!$fileName || !file_exists($real_path)) {
        return '/0-ECFEcoride/photos/default-user.png';
    } else {
        return "/0-ECFEcoride/photos/" . $fileName;
    }

}

/**
 * Function to manage the responsive menu display
 * @param mixed $asListItem true if small screen
 * @return void
 */
function renderNavigationLinks($asListItem = false)
{
    $tagOpen = $asListItem ? '<li>' : '';
    $tagClose = $asListItem ? '</li>' : '';

    echo $tagOpen . '<a id="home-page" href="home_page.php">Accueil</a>' . $tagClose;
    echo $tagOpen . '<a id="carpool-button" href="carpool_search.php">Covoiturages</a>' . $tagClose;
    echo $tagOpen . '<a href="#">Contact</a>' . $tagClose;

    if (session_status() === PHP_SESSION_NONE)
        session_start();

    if (isset($_SESSION['user_id'])) {
        switch ($_SESSION['role_user']) {
            case 1:
            case 2:
            case 3:
                echo $tagOpen . '<a class="btn border-white" id="userSpace" href="user_space.php">Espace Utilisateur</a>' . $tagClose;
                break;
            case 4:
                echo $tagOpen . '<a class="btn border-white" id="employeeSpace" href="employee_space.php">Espace Employé</a>' . $tagClose;
                break;
            case 5:
                echo $tagOpen . '<a class="btn border-white" id="adminSpace" href="admin_space.php">Espace Administrateur</a>' . $tagClose;
                break;
        }
        echo $tagOpen . '<a id="logoutButton" href="login.php"> <img src="../icons/Deconnexion.png" alt="logout" class="logout-btn"> </a>' . $tagClose;
    } else {
        echo $tagOpen . '<a class="btn border-white" id="loginButton" href="login.php">Connexion</a>' . $tagClose;
    }
}