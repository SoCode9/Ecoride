<?php

require_once __DIR__ . "/../class/Travel.php";
class Reservation
{
    private PDO $pdo;
    private ?int $reservationId = null;
    private ?string $userId;
    private ?string $travelId;
    private ?bool $isValidated;
    private ?int $creditSpent = null;
    private ?string $badComment;

    public function __construct(PDO $pdo, ?string $userId = null, ?string $travelId = null, ?bool $isValidated = null, ?string $badComment = null)
    {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->travelId = $travelId;
        $this->isValidated = $isValidated;
        $this->badComment = $badComment;
    }

    /**
     * count the number of passengers in a carpool
     * @param string $travelId
     * @return int
     */
    public function countPassengers(string $travelId): int
    {
        $sql = "SELECT COUNT(*) AS 'seats_allocated' FROM reservations WHERE travel_id = :travelId";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_STR);
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    /**
     * list of carpools "in validation" to be validated by the user (passenger or driver)
     * @param string $userId
     * @return array
     */
    public function getCarpoolsToValidate(string $userId): array
    {
        $sql = "SELECT travels.*, users.pseudo, users.photo, ratings.rating, reservations.is_validated, reservations.id AS reservationId 
        FROM travels 
        LEFT JOIN reservations ON reservations.travel_id = travels.id AND reservations.user_id = :userConnectedId
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        WHERE (travel_status = 'in validation') AND ((reservations.user_id =:userConnectedId AND reservations.is_validated = 0) OR (travels.driver_id =:userConnectedId))
        GROUP BY travels.id, users.pseudo
        ORDER BY travel_date ASC ";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(":userConnectedId", $userId, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * list of carpools "not started" or "in progress" 
     * @param string $userId
     * @return array
     */
    public function getCarpoolsNotStarted(string $userId): array
    {
        $sql = "SELECT travels.*, users.pseudo, users.photo, ratings.rating FROM travels 
        LEFT JOIN reservations ON reservations.travel_id = travels.id 
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        
        WHERE ((travel_status = 'not started') OR (travel_status = 'in progress')) 
        AND ((reservations.user_id =:userConnectedId1) OR (driver.user_id = :userConnectedId2))
        GROUP BY travels.id
        ORDER BY travel_date ASC ";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(":userConnectedId1", $userId, PDO::PARAM_STR);
        $statement->bindParam(":userConnectedId2", $userId, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * list of carpools "ended" and validated or "cancelled"
     * @param string $userId
     * @return array
     */
    public function getCarpoolsCompleted(string $userId): array
    {
        $sql = "SELECT travels.*, users.pseudo,  users.photo, ratings.rating, reservations.is_validated FROM travels 
        LEFT JOIN reservations ON reservations.travel_id = travels.id 
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        WHERE (reservations.user_id =:userConnectedId1 AND (travel_status = 'cancelled' OR reservations.is_validated = 1)) OR (driver.user_id = :userConnectedId2 AND (travel_status = 'cancelled' OR travel_status = 'ended'))
        GROUP BY travels.id
        ORDER BY travel_date ASC ";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(":userConnectedId1", $userId, PDO::PARAM_STR);
        $statement->bindParam(":userConnectedId2", $userId, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * When a user (passenger or driver) cancel a carpool
     * @param string $userId
     * @param string $travelId
     * @throws \Exception
     * @return void
     */
    public function cancelCarpool(string $userId, string $travelId): void
    {

        try {
            $this->pdo->beginTransaction();

            $reservationId = $this->getReservationId($userId, $travelId);
            $creditSpent = $this->getCreditSpent($reservationId);
            $this->setCreditToUser($userId, $creditSpent);

            $sql = 'DELETE FROM reservations WHERE user_id = :userId AND travel_id = :travelId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':userId', $userId);
            $statement->bindParam(':travelId', $travelId);
            $statement->execute();

            $this->pdo->commit();

        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erreur dans cancelCarpool : " . $e->getMessage());
            throw new Exception("Impossible d'annuler le covoiturage");
        }
    }

    /**
     * When a carpool is validated (YES)
     * @param int $reservationId
     * @throws \Exception
     * @return void
     */
    public function validateCarpoolYes(int $reservationId): void
    {
        try {
            $this->pdo->beginTransaction();

            $creditSpent = $this->getCreditSpent($reservationId);

            $driverId = $this->getDriverIdFromReservation($reservationId);
            $this->setCreditToUser($driverId, $creditSpent);

            $this->markAsValidated($reservationId);

            $travelId = $this->getTravelIdFromReservation($reservationId);
            $notValidated = $this->getReservationsNotValidatedOfACarpool($travelId);

            if (empty($notValidated)) {
                require_once __DIR__ . "/../class/Travel.php";
                $travel = new Travel($this->pdo, $travelId);

                $travel->setTravelStatus('ended', $travelId);

                $this->setCreditToUser($driverId, -2);
            }

            $this->pdo->commit();

        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erreur dans validateCarpoolYes : " . $e->getMessage());
            throw new Exception("Impossible de valider la réservation");
        }
    }

    /**
     * When a carpool is validated (NO)
     * @param int $reservationId
     * @param string $badComment
     * @return void
     */
    public function validateCarpoolNo(int $reservationId, string $badComment): void
    {
        $this->addBadComment($reservationId, $badComment);
        $this->markAsValidated($reservationId);
    }

    /**
     * When the employee has resolved a bad comment
     * @param int $reservationId
     * @throws \Exception
     * @return void
     */
    public function resolveBadComment(int $reservationId): void
    {
        try {
            $this->pdo->beginTransaction();

            $this->markBadCommentAsValidated($reservationId);
            $this->setCreditToUser($this->getDriverIdFromReservation($reservationId), $this->getCreditSpent($reservationId));

            $travelId = $this->getTravelIdFromReservation($reservationId);

            $reservationsNotValidatedOfTheCarpool = $this->getReservationsNotValidatedOfACarpool($travelId);

            if (empty($reservationsNotValidatedOfTheCarpool)) {
                $travel = new Travel($this->pdo, $travelId);
                $travel->setTravelStatus('ended', $travelId);

                //substract 2 credits to driver
                $this->setCreditToUser($this->getDriverIdFromReservation($reservationId), -2);
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erreur dans resolveBadComment : " . $e->getMessage());
            throw new Exception("Impossible de résoudre le litige");
        }
    }

    /**
     * Get the driver id of a reservation
     * @param int $reservationId
     * @throws \Exception
     * @return string
     */
    public function getDriverIdFromReservation(int $reservationId): string
    {
        $travelId = $this->getTravelIdFromReservation($reservationId);

        $sql = 'SELECT driver_id FROM travels WHERE id = :travelId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result || !isset($result['driver_id'])) {
            throw new Exception("Aucun chauffeur trouvé pour le trajet #$travelId");
        }

        return $result['driver_id'];
    }

    /**
     * Get the reservation id of a reservation
     * @param string $userId
     * @param string $travelId
     * @throws \Exception
     * @return int
     */
    private function getReservationId(string $userId, string $travelId): int
    {
        $sql = 'SELECT id FROM reservations WHERE user_id = :userId AND travel_id = :travelId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_STR);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_STR);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result || !isset($result['id'])) {
            throw new Exception("Aucune réservation n'a été trouvée");
        }

        return $result['id'];
    }

    /**
     * Get the travel id of a reservation
     * @param int $reservationId
     * @throws \Exception
     */
    private function getTravelIdFromReservation(int $reservationId): string
    {
        $sql = 'SELECT travel_id FROM reservations WHERE id = :reservationId ';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result || !isset($result['travel_id'])) {
            throw new Exception("Erreur lors du chargement des informations de la réservation");
        }

        return $result['travel_id'];
    }

    /**
     * Get the credits spent on a reservation
     * @param int $reservationId
     * @throws \Exception
     * @return int
     */
    private function getCreditSpent(int $reservationId): int
    {
        $sql = 'SELECT credits_spent FROM reservations WHERE id = :reservationId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result || !isset($result['credits_spent'])) {
            throw new Exception("Erreur lors de la récupération des crédits");
        }

        return $result['credits_spent'];
    }

    /**
     * Get the passengers of a carpool
     * @param string $travelId
     * @return array
     */
    public function getPassengersOfTheCarpool(string $travelId): array
    {
        $sql = 'SELECT user_id FROM reservations WHERE travel_id = :travelId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the reservations not validated of a carpool
     * @param string $travelId
     * @return array
     */
    public function getReservationsNotValidatedOfACarpool(string $travelId): array
    {
        $sql = 'SELECT * FROM reservations WHERE (is_validated = 0 OR bad_comment_validated = 0) AND travel_id = :travelId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam('travelId', $travelId, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves reservations that include a bad comment not yet validated
     * @param int $limit Number of results to return (default: 5)
     * @param int $offset Number of results to skip (default: 0)
     * @return array Array of associative results including passenger and driver info, travel date and cities, and reservation details
     */
    public function getBadComments(int $limit = 5, int $offset = 0): array
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

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count all bad comments not yet validated
     * @return int
     */
    public function countAllBadComments(): int
    {
        $sql = "SELECT COUNT(*) FROM reservations  WHERE bad_comment IS NOT NULL AND bad_comment_validated =0";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return (int) $statement->fetchColumn();
    }

    /**
     * Set credits to user
     * @param string $userId
     * @param int $creditToSend
     * @throws \Exception
     * @return void
     */
    private function setCreditToUser(string $userId, int $creditToSend): void
    {
        $sql = 'UPDATE users SET credit=credit+:creditToSend WHERE id = :userId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':creditToSend', $creditToSend, PDO::PARAM_INT);
        $statement->bindParam(':userId', $userId, PDO::PARAM_STR);

        if (!$statement->execute()) {
            throw new Exception("Échec de la mise à jour des crédits de l'utilisateur #$userId");
        }

        if ($statement->rowCount() === 0) {
            throw new Exception("Aucun utilisateur trouvé avec l'ID #$userId");
        }
    }

    /**
     * Set the reservation as validated
     * @param int $reservationId
     * @throws \Exception
     * @return void
     */
    private function markAsValidated(int $reservationId): void
    {
        $sql = 'UPDATE reservations SET is_validated = 1 WHERE id = :reservationId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        $statement->execute();
        if (!$statement->execute()) {
            throw new Exception("Échec de la validation du covoiturage");
        }
    }

    /**
     * If passenger put a bad comment on a carpool, it's added in database in reservations' table
     * @param int $reservationId
     * @param string $badComment
     * @throws \Exception
     * @return void
     */
    private function addBadComment(int $reservationId, string $badComment): void
    {
        $sql = 'UPDATE reservations SET bad_comment =:badComment, bad_comment_validated = 0  WHERE id = :reservationId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':badComment', $badComment, PDO::PARAM_STR);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        $statement->execute();
        if (!$statement->execute()) {
            throw new Exception("Échec de l'enregistrement du litige");
        }

    }

    /**
     * Put the bad comment as validated
     * @param int $reservationId
     * @throws \Exception
     * @return void
     */
    private function markBadCommentAsValidated(int $reservationId): void
    {
        $sql = 'UPDATE reservations SET bad_comment_validated = 1  WHERE id = :reservationId';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        $statement->execute();
        if (!$statement->execute()) {
            throw new Exception("Échec de la résolution du litige");
        }
    }
}