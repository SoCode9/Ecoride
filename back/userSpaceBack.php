<?php

require_once "../database.php";
require_once "../class/User.php";
require_once "../class/Driver.php";
require_once "../class/Car.php";
require_once "../class/Reservation.php";
require_once "../class/Travel.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idUser = $_SESSION['user_id'];

$connectedUser = new User($pdo, $idUser, null, null, null);
if (($connectedUser->getIdRole() === 2) or ($connectedUser->getIdRole() === 3)) {
    $connectedDriver = new Driver($pdo, $connectedUser->getId());
    $carsOfConnectedDriver = new Car($pdo, $connectedDriver->getId(), null);
    $cars = $carsOfConnectedDriver->getCars();
}

$usersReservations = new Reservation($pdo, $idUser);
$carpoolListToValidate = $usersReservations->carpoolFinishedToValidate($pdo, $idUser);

$carpoolListNotStarted = $usersReservations->carpoolNotStarted($pdo, $idUser);

$carpoolListFinishedAndValidated = $usersReservations->carpoolFinishedAndValidated($pdo, $idUser);

/*Cars' form*/
// Request to retrieve car's brands 
$stmt = $pdo->query("SELECT id, name FROM brands ORDER BY name ASC");
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_GET['action'])) {
    /*Cancel a carpool*/
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'cancel_carpool') {
        $idTravel = $_GET['id'];
        $travel = new Travel($pdo, $idTravel);
        /*If I'm the driver*/
        if ($travel->getDriverId() === $idUser) {
            $reservation = new Reservation($pdo, null, $idTravel);

            $passengersIdOfTheCarpool = $reservation->getPassengersOfTheCarpool($pdo, $idTravel);
            $travelDate = formatDateLong($travel->getDate($idTravel));
            $travelDeparture = $travel->getDepartureCity();
            $travelArrival = $travel->getArrivalCity();
            $message = "Le covoiturage du $travelDate de $travelDeparture à $travelArrival a été annulé par le chauffeur.";

            foreach ($passengersIdOfTheCarpool as $passengerId) {
                $passenger = new User($pdo, $passengerId['user_id']);
                $passengerMail = $passenger->getMail();
                mail($passengerMail, 'Annulation du covoiturage', $message, 'FROM: test@ecoride.local');
                $reservation->cancelCarpool($pdo, $passengerId['user_id'], $idTravel);
            }
            $travel->setTravelStatus('cancelled', $idTravel); //change travel's status

            header('Location: ../index/userSpaceIndex.php');
            $_SESSION['success_message'] = "Le covoiturage a été annulé. Les passagers ont reçu un mail leur en informant.";

            /*If I'm only a passenger*/
        } elseif ($travel->getDriverId() !== $idUser) {
            $usersReservations->cancelCarpool($pdo, $idUser, $idTravel);
            header('Location: ../index/userSpaceIndex.php');

        }
    }

    /*Start a carpool*/
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'start_carpool') {
        $idTravel = $_GET['id'];
        $travel = new Travel($pdo, $idTravel);
        $travel->setTravelStatus('in progress', $idTravel);
        header('Location: ../index/userSpaceIndex.php');
    }

    /*Complete a carpool*/
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'complete_carpool') {
        $idTravel = $_GET['id'];
        $travel = new Travel($pdo, $idTravel);
        $travel->setTravelStatus('in validation', $idTravel);
        header('Location: ../index/userSpaceIndex.php');

        //send an email to passengers
        $reservation = new Reservation($pdo, null, $idTravel);

        $passengersIdOfTheCarpool = $reservation->getPassengersOfTheCarpool($pdo, $idTravel);
        $travelDate = formatDateLong($travel->getDate($idTravel));
        $travelDeparture = $travel->getDepartureCity();
        $travelArrival = $travel->getArrivalCity();
        $message = "Le covoiturage du $travelDate de $travelDeparture à $travelArrival est terminé ! 
        Merci de valider que tout s'est bien passé en vous rendant sur votre espace utilisateur. 
        N'hésitez pas à soumettre un avis.";

        foreach ($passengersIdOfTheCarpool as $passengerId) {
            $passenger = new User($pdo, $passengerId['user_id']);
            $passengerMail = $passenger->getMail();
            mail($passengerMail, 'Validation du covoiturage', $message, 'FROM: test@ecoride.local');
        }
        $_SESSION['success_message'] = "Vos crédits seront mis à jour une fois que les passagers auront validé le covoiturage.";


    }
}
