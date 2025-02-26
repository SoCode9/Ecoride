<?php

//to store useful functions for all pages


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
 * @param string $dateToFormat //give the date to format
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
 * To disply times for users (ex. 12:00)
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

/**
 * calculation of placesAvailable with informations in DB
 * @param int $placesOfferedNb //field in DB
 * @param int $placesAllowedNb //field in DB
 * @return int
 */
function placesAvailable(int $placesOfferedNb, int $placesAllocatedNb): int
{
    $placesAvailable = $placesOfferedNb - $placesAllocatedNb;
    return $placesAvailable;
}


function formatEco(bool $nbEco): string
{
    if ($nbEco == 1) {
        return '<img src="../icons/Arbre 1.png" alt="Arbre" width="20px">' . " Economique";
    }
    return "";
}