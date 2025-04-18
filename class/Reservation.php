<?php

class Reservation
{
    private ?PDO $pdo;
    private int $reservationId;
    private ?int $userId;
    private ?int $travelId;

    private ?bool $isValidated;
    private ?int $creditSpent;
    private ?string $badComment;

    public function __construct($pdo, ?int $userId = null, ?int $travelId = null, ?bool $isValidated = null, ?string $badComment = null)
    {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->travelId = $travelId;
        $this->isValidated = $isValidated;
        $this->badComment = $badComment;
    }

    public function nbPassengerInACarpool($pdo, $travelId)
    {
        $sql = "SELECT COUNT(travel_id)AS 'seats_allocated' FROM reservations WHERE travel_id = :travelId";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
        $statement->execute();
        $nbPassengerInACarpool = $statement->fetch();
        return $nbPassengerInACarpool['seats_allocated'];
    }

    public function carpoolFinishedToValidate($pdo, $userId)
    {
        $sql = "SELECT travels.*, users.pseudo, users.photo, ratings.rating, reservations.is_validated, reservations.id AS reservationId FROM travels 
        JOIN reservations ON reservations.travel_id = travels.id AND reservations.user_id = :userConnected_id
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        WHERE (travel_status = 'in validation') AND ((reservations.user_id =:userConnected_id AND reservations.is_validated = 0) OR (travels.driver_id =:userConnected_id))
        GROUP BY travels.id, users.pseudo
        ORDER BY travel_date ASC ";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(":userConnected_id", $userId, PDO::PARAM_INT);
        $statement->execute();

        $carpoolListToValidate = $statement->fetchAll();

        return $carpoolListToValidate;
    }

    public function carpoolNotStarted($pdo, $userId)
    {
        $sql = "SELECT travels.*, users.pseudo, users.photo, ratings.rating FROM travels 
        LEFT JOIN reservations ON reservations.travel_id = travels.id 
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        
        WHERE ((travel_status = 'not started') OR (travel_status = 'in progress')) 
        AND ((reservations.user_id =:userConnected_id) OR (driver.user_id = :user_connected_id))
        GROUP BY travels.id
        ORDER BY travel_date ASC ";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(":userConnected_id", $userId, PDO::PARAM_INT);
        $statement->bindParam(":user_connected_id", $userId, PDO::PARAM_INT);
        $statement->execute();

        $carpoolListNotStarted = $statement->fetchAll();

        return $carpoolListNotStarted;
    }

    public function carpoolFinishedAndValidated($pdo, $userId)
    {
        $sql = "SELECT travels.*, users.pseudo,  users.photo, ratings.rating, reservations.is_validated FROM travels 
        LEFT JOIN reservations ON reservations.travel_id = travels.id 
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        WHERE (reservations.user_id =:userConnected_id AND (travel_status = 'cancelled' OR reservations.is_validated = 1)) OR (driver.user_id = :user_connected_id AND (travel_status = 'cancelled' OR travel_status = 'ended'))
        GROUP BY travels.id
        ORDER BY travel_date ASC ";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(":userConnected_id", $userId, PDO::PARAM_INT);
        $statement->bindParam(":user_connected_id", $userId, PDO::PARAM_INT);
        $statement->execute();

        $carpoolListFinishedAndValidated = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $carpoolListFinishedAndValidated;
    }

    /**
     * When a user (passenger or driver) cancel a carpool
     * 1. passengers get their credits back
     * 2. reservations are removed from the reservations's table
     * @param mixed $pdo
     * @param mixed $userId
     * @param mixed $travelId
     * @return void
     */
    public function cancelCarpool($pdo, $userId, $travelId)
    {

        $reservationId = $this->getReservationId($pdo, $userId, $travelId);

        $creditSpentOnTheReservation = $this->getCreditSpent($pdo, $reservationId);

        $this->setCreditToUser($pdo, $userId, $creditSpentOnTheReservation);

        $sql = 'DELETE FROM reservations WHERE user_id = :userId AND travel_id = :travelId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
        try {
            $statement->execute();

        } catch (Exception $e) {
            new Exception("Erreur : " . $e->getMessage());
        }
    }

    /**
     * if the carpool is validated (YES)
     * 1.get credit spent by the passenger (in reservations' table)
     * 2. update credit of the driver
     * 3. set the reservation as validated
     * 4. if all the carpool's reservations are validated : put the carpool as "ended" + add 2 credits to the admin and substract 2 credits to the driver
     * @param mixed $pdo
     * @param mixed $reservationId
     * @return void
     */
    public function validateCarpoolYes($pdo, $reservationId)
    {
        $creditSpentOnTheReservation = $this->getCreditSpent($pdo, $reservationId);

        $this->setCreditToUser($pdo, $this->getDriverIdFromReservation($pdo, $reservationId), $creditSpentOnTheReservation);
        $this->setValidate($pdo, $reservationId);

        $travelId = $this->getTravelIdFromReservation($pdo, $reservationId);

        $reservationsNotValidatedOfTheCarpool = $this->getReservationsNotValidatedOfACarpool($pdo, $travelId);

        if (empty($reservationsNotValidatedOfTheCarpool)) {
            require_once  __DIR__ . "/../class/Travel.php";
            $travel = new Travel($pdo, $travelId);
            $travel->setTravelStatus('ended', $travelId);

            //substract 2 credits to driver
            $this->setCreditToUser($pdo, $this->getDriverIdFromReservation($pdo, $reservationId), -2);
        }
    }

    /**
     * if the carpool is validated (NO)
     * 1. add bad comment (in reservations' table)
     * 2. set the reservation as validated
     * @param mixed $pdo
     * @param mixed $reservationId
     * @param mixed $badComment
     * @return void
     */
    public function validateCarpoolNo($pdo, $reservationId, $badComment)
    {
        $this->setBadComment($pdo, $reservationId, $badComment);
        $this->setValidate($pdo, $reservationId);
    }

    /**
     * When the employee has resolved a bad comment
     * 1. set the bad comment as validated
     * 2. the credits' driver are updated
     * 3. if all the carpool's reservations are validated : put the carpool as "ended" + add 2 credits to the admin and substract 2 credits to the driver
     * @param mixed $pdo
     * @param mixed $reservationId
     * @return void
     */
    public function resolveBadComment($pdo, $reservationId)
    {
        try {
            $this->setBadCommentValidated($pdo, $reservationId);
            $this->setCreditToUser($pdo, $this->getDriverIdFromReservation($pdo, $reservationId), $this->getCreditSpent($pdo, $reservationId));

            $travelId = $this->getTravelIdFromReservation($pdo, $reservationId);

            $reservationsNotValidatedOfTheCarpool = $this->getReservationsNotValidatedOfACarpool($pdo, $travelId);

            if (empty($reservationsNotValidatedOfTheCarpool)) {
                require_once "../../class/Travel.php";
                $travel = new Travel($pdo, $travelId);
                $travel->setTravelStatus('ended', $travelId);

                //substract 2 credits to driver
                $this->setCreditToUser($pdo, $this->getDriverIdFromReservation($pdo, $reservationId), -2);
            }

        } catch (Exception $e) {
            new Exception("Erreur : " . $e->getMessage());
        }
    }

    /**
     * Get the driver id of a reservation
     * @param mixed $pdo
     * @param mixed $reservationId
     */
    public function getDriverIdFromReservation($pdo, $reservationId)
    {
        $travelId = $this->getTravelIdFromReservation($pdo, $reservationId);

        $sql = 'SELECT driver_id FROM travels WHERE id = :travelId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $driverId = $statement->fetch(PDO::FETCH_ASSOC);

            return $driverId['driver_id'];
        } catch (Exception $e) {
            new Exception("Erreur lors de la récupération de l'id du chauffeur : " . $e->getMessage());
        }
    }

    /**
     * Get the reservation id of a reservation
     * @param mixed $pdo
     * @param mixed $userId //give the passenger id
     * @param mixed $travelId //give the travel id
     */
    private function getReservationId($pdo, $userId, $travelId)
    {
        $sql = 'SELECT id FROM reservations WHERE user_id = :userId AND travel_id = :travelId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $reservationId = $statement->fetch(PDO::FETCH_ASSOC);
            return $reservationId['id'];
        } catch (Exception $e) {
            new Exception("Erreur : " . $e->getMessage());
        }
    }

    private function getTravelIdFromReservation($pdo, $reservationId)
    {
        $sql = 'SELECT travel_id FROM reservations WHERE id = :reservationId ';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $travelId = $statement->fetch(PDO::FETCH_ASSOC);
            return $travelId['travel_id'];
        } catch (Exception $e) {
            new Exception("Erreur lors du chargement des informations de la réservation : " . $e->getMessage());
        }
    }

    private function getCreditSpent($pdo, $reservationId)
    {
        $sql = 'SELECT credits_spent FROM reservations WHERE id = :reservationId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $creditSpentOnTheReservation = $statement->fetch(PDO::FETCH_ASSOC);
            return $creditSpentOnTheReservation['credits_spent'];
        } catch (Exception $e) {
            new Exception("Erreur lors de la récupération des crédits : " . $e->getMessage());
        }
    }

    public function getPassengersOfTheCarpool($pdo, $travelId)
    {
        $sql = 'SELECT user_id FROM reservations WHERE travel_id = :travelId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $passengersOfTheCarpool = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $passengersOfTheCarpool;
        } catch (Exception $e) {
            new Exception("Erreur : " . $e->getMessage());
        }
    }

    public function getReservationsNotValidatedOfACarpool($pdo, $travelId)
    {
        $sql = 'SELECT * FROM reservations WHERE (is_validated = 0 OR bad_comment_validated = 0) AND travel_id = :travelId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam('travelId', $travelId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $reservationsNotValidated = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $reservationsNotValidated;
        } catch (Exception $e) {
            new Exception("Erreur lors du la récupération des covoiturages non validés : " . $e->getMessage());

        }
    }

    public function getBadComments($limit = 5, $offset = 0)
    {
        $sql = 'SELECT reservations.*,passenger.pseudo AS pseudoPassenger, passenger.mail AS mailPassenger, driver.pseudo AS pseudoDriver, driver.mail AS mailDriver, driver.id AS idDriver, travels.travel_date, travels.travel_departure_city, travels.travel_arrival_city, travels.id AS travelId FROM reservations 
        JOIN users AS passenger ON passenger.id = reservations.user_id
        JOIN travels ON travels.id = reservations.travel_id
        JOIN users AS driver ON driver.id = travels.driver_id
        WHERE bad_comment IS NOT NULL AND bad_comment_validated =0
        ORDER BY travels.travel_date ASC
        LIMIT :limit OFFSET :offset';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $reservationsWithBadComment = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $reservationsWithBadComment;
    }

    public function countAllBadComments()
    {
        $sql = "SELECT COUNT(*) FROM reservations  WHERE bad_comment IS NOT NULL AND bad_comment_validated =0";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return (int) $statement->fetchColumn();
    }

    private function setCreditToUser($pdo, $userId, $creditToSent)
    {
        $sql = 'UPDATE users SET credit=credit+:creditToSent WHERE id = :userId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':creditToSent', $creditToSent, PDO::PARAM_INT);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        try {
            $statement->execute();
        } catch (Exception $e) {
            new Exception("Erreur lors de la mise à jour des crédits de l'utilisateur : " . $e->getMessage());
        }
    }

    private function setValidate($pdo, $reservationId)
    {
        $sql = 'UPDATE reservations SET is_validated = 1 WHERE id = :reservationId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        try {
            return $statement->execute();
        } catch (Exception $e) {
            new Exception("Erreur lors de la validation :" . $e->getMessage());
        }
    }

    /**
     * If passenger put a bad comment on a carpool, it's added in database in reservations' table
     * @param mixed $pdo
     * @param mixed $reservationId
     * @param mixed $badComment
     * @return void
     */
    private function setBadComment($pdo, $reservationId, $badComment)
    {
        $sql = 'UPDATE reservations SET bad_comment =:badComment, bad_comment_validated = 0  WHERE id = :reservationId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':badComment', $badComment, PDO::PARAM_STR);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        try {
            $statement->execute();
        } catch (Exception $e) {
            new Exception("Erreur : " . $e->getMessage());
        }

    }

    private function setBadCommentValidated($pdo, $reservationId)
    {
        $sql = 'UPDATE reservations SET bad_comment_validated = 1  WHERE id = :reservationId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch (Exception $e) {
            new Exception("Erreur : " . $e->getMessage());
        }
    }
}