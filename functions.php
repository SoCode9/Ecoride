<?php

$host = $_SERVER['HTTP_HOST'];

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
        return '<img src="' . BASE_URL . ' /icons/Arbre 1.png" alt="Arbre" width="20px">' . " Economique";
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
    if (!$fileName) {
        return BASE_URL . '/photos/default-user.png';
    }

    $safeFileName = basename($fileName);
    $realPath = __DIR__ . '/photos/' . $safeFileName;

    if (!file_exists($realPath)) {
        return BASE_URL . '/photos/default-user.png';
    }

    return BASE_URL . '/photos/' . $safeFileName;
}

/**
 * Function to manage the responsive menu display
 * @param mixed $asListItem true if small screen
 * @return void
 */
function renderNavigationLinks($asListItem = false)
{
    $base = BASE_URL;
    $tagOpen = $asListItem ? '<li>' : '';
    $tagClose = $asListItem ? '</li>' : '';

    echo $tagOpen . "<a id='home-page' href='{$base}/index.php'>Accueil</a>" . $tagClose;
    echo $tagOpen . "<a id='carpool-button' href='{$base}/controllers/carpool_search.php'>Covoiturages</a>" . $tagClose;
    echo $tagOpen . "<a id='contact-button' href='{$base}/controllers/contact.php'>Contact</a>" . $tagClose;

    if (isset($_SESSION['user_id'])) {
        switch ($_SESSION['role_user']) {
            case 1:
            case 2:
            case 3:
                echo $tagOpen . "<a class='btn border-white' id='user-space' href='{$base}/controllers/user_space.php'>Espace Utilisateur</a>" . $tagClose;
                break;
            case 4:
                echo $tagOpen . "<a class='btn border-white' id='employee-space' href='{$base}/controllers/employee_space.php'>Espace Employé</a>" . $tagClose;
                break;
            case 5:
                echo $tagOpen . "<a class='btn border-white' id='admin-space' href='{$base}/controllers/admin_space.php'>Espace Administrateur</a>" . $tagClose;
                break;
        }
        echo $tagOpen . "<a id='logout-button' href='{$base}/controllers/login.php'> 
            <img src='{$base}/icons/Deconnexion.png' alt='logout' class='logout-btn'> 
        </a>" . $tagClose;
    } else {
        echo $tagOpen . "<a class='btn border-white' id='login-button' href='{$base}/controllers/login.php'>Connexion</a>" . $tagClose;
    }
}