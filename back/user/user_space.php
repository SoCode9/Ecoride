<?php
require_once __DIR__ . "/../../back/user/auth.php";
requireLogin(); // Checks whether a user is logged in
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/User.php";
require_once __DIR__ . "/../../class/Driver.php";
require_once __DIR__ . "/../../class/Car.php";
require_once __DIR__ . "/../../class/Reservation.php";
require_once __DIR__ . "/../../class/Travel.php";

$pdo = pdo();

$userId = $_SESSION['user_id'];

try {
    $connectedUser = User::fromId($pdo, $userId);
    if (($connectedUser->getIdRole() === 2) or ($connectedUser->getIdRole() === 3)) {
        $connectedDriver = new Driver($pdo, $connectedUser->getId());
        $carsOfConnectedDriver = new Car($pdo, $connectedDriver->getId(), null);
        $cars = $carsOfConnectedDriver->getCars();
    }

    $usersReservations = new Reservation($pdo, $userId);
    $carpoolListToValidate = $usersReservations->getCarpoolsToValidate($userId);

    $carpoolListNotStarted = $usersReservations->getCarpoolsNotStarted($userId);

    $carpoolListFinishedAndValidated = $usersReservations->getCarpoolsCompleted($userId);

    /*Cars' form*/
    // Request to retrieve car's brands 
    $statement = $pdo->query("SELECT id, name FROM brands ORDER BY name ASC");
    $brands = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error in user_space (load info) : " . $e->getMessage());
    header('Location: ../index.php');
    $_SESSION['error_message'] = "Une erreur est survenue";
    exit;
}

if (isset($_GET['action'])) {
    /*Cancel a carpool*/
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'cancel_carpool') {
        $idTravel = $_GET['id'];

        try {
            $travel = new Travel($pdo, $idTravel);
            /*If I'm the driver*/
            if ($travel->getDriverId() === $userId) {
                $reservation = new Reservation($pdo, null, $idTravel);

                $passengersIdOfTheCarpool = $reservation->getPassengersOfTheCarpool($idTravel);
                $travelDate = formatDateLong($travel->getDate($idTravel));
                $travelDeparture = $travel->getDepartureCity();
                $travelArrival = $travel->getArrivalCity();
                $message = "Le covoiturage du $travelDate de $travelDeparture à $travelArrival a été annulé par le chauffeur.";

                foreach ($passengersIdOfTheCarpool as $passengerId) {
                    $passenger = User::fromId($pdo, $passengerId['user_id']);
                    $passengerMail = $passenger->getMail();
                    mail($passengerMail, 'Annulation du covoiturage', $message, 'FROM: test@ecoride.local');
                    $reservation->cancelCarpool($passengerId['user_id'], $idTravel);
                }
                $travel->setTravelStatus('cancelled', $idTravel); //change travel's status

                header('Location: ../../controllers/user_space.php?tab=carpools');
                $_SESSION['success_message'] = "Le covoiturage a été annulé. Les passagers ont reçu un mail leur en informant.";
                exit;

                /*If I'm only a passenger*/
            } elseif ($travel->getDriverId() !== $userId) {
                $usersReservations->cancelCarpool($userId, $idTravel);
                header('Location: ../../controllers/user_space.php?tab=carpools');
                $_SESSION['success_message'] = "Vous ne participez plus au covoiturage. Vos crédits vous ont été restitués.";
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in cancel a carpool : " . $e->getMessage());
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../../controllers/user_space.php?tab=carpools');
            exit;
        }
    }

    /*Start a carpool*/
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'start_carpool') {
        try {
            $idTravel = $_GET['id'];
            $travel = new Travel($pdo, $idTravel);
            $travel->setTravelStatus('in progress', $idTravel);
            header('Location: ../../controllers/user_space.php?tab=carpools');
            $_SESSION['success_message'] = "Le covoiturage a débuté.";
            exit;
        } catch (Exception $e) {
            error_log("Error in start a carpool : " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue";
            header('Location: ../../controllers/user_space.php?tab=carpools');
            exit;
        }
    }

    /*Complete a carpool*/
    if ($_SERVER['REQUEST_METHOD'] === "GET" && $_GET['action'] == 'complete_carpool') {
        try {
            $idTravel = $_GET['id'];
            $travel = new Travel($pdo, $idTravel);
            $travel->setTravelStatus('in validation', $idTravel);
            header('Location: ../../controllers/user_space.php?tab=carpools');

            //send an email to passengers
            $reservation = new Reservation($pdo, null, $idTravel);

            $passengersIdOfTheCarpool = $reservation->getPassengersOfTheCarpool($idTravel);
            $travelDate = formatDateLong($travel->getDate($idTravel));
            $travelDeparture = $travel->getDepartureCity();
            $travelArrival = $travel->getArrivalCity();
            $message = "Le covoiturage du $travelDate de $travelDeparture à $travelArrival est terminé ! 
        Merci de valider que tout s'est bien passé en vous rendant sur votre espace utilisateur. 
        N'hésitez pas à soumettre un avis.";

            foreach ($passengersIdOfTheCarpool as $passengerId) {
                $passenger = User::fromId($pdo, $passengerId['user_id']);
                $passengerMail = $passenger->getMail();
                mail($passengerMail, 'Validation du covoiturage', $message, 'FROM: test@ecoride.local');
            }
            $_SESSION['success_message'] = "Vos crédits seront mis à jour une fois que les passagers auront validé le covoiturage.";
        } catch (Exception $e) {
            error_log("Error in complete a carpool : " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue";
            header('Location: ../../controllers/user_space.php?tab=carpools');
            exit;
        }
    }
}
