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
        try {
            $sql = "SELECT COUNT(*) AS 'seats_allocated' FROM reservations WHERE travel_id = :travelId";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':travelId', $travelId, PDO::PARAM_STR);
            $statement->execute();

            return (int) $statement->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error in countPassengers() : " . $e->getMessage());
            throw new Exception("Impossible de compter les passagers");
        }
    }

    /**
     * list of carpools "in validation" to be validated by the user (passenger or driver)
     * @param string $userId
     * @return array
     */
    public function getCarpoolsToValidate(string $userId): array
    {
        try {
            $sql = "SELECT travels.*, users.pseudo, users.photo, ratings.rating, reservations.is_validated, reservations.id AS reservationId 
                FROM travels 
                LEFT JOIN reservations ON reservations.travel_id = travels.id AND reservations.user_id = :userConnectedId1
                JOIN driver ON driver.user_id = travels.driver_id 
                JOIN users ON users.id = travels.driver_id
                LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
                WHERE (travel_status = 'in validation') AND ((reservations.user_id =:userConnectedId2 AND reservations.is_validated = 0) OR (travels.driver_id =:userConnectedId3))
                GROUP BY travels.id, users.pseudo
                ORDER BY travel_date ASC ";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":userConnectedId1", $userId, PDO::PARAM_STR);
            $statement->bindParam(":userConnectedId2", $userId, PDO::PARAM_STR);
            $statement->bindParam(":userConnectedId3", $userId, PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getCarpoolsToValidate() : " . $e->getMessage());
            throw new Exception("Impossible d'obtenir les covoiturages à valider");
        }
    }

    /**
     * list of carpools "not started" or "in progress" 
     * @param string $userId
     * @return array
     */
    public function getCarpoolsNotStarted(string $userId): array
    {
        try {
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
        } catch (PDOException $e) {
            error_log("Database error in getCarpoolsNotStarted() : " . $e->getMessage());
            throw new Exception("Impossible d'obtenir les covoiturages non commencé");
        }
    }

    /**
     * list of carpools "ended" and validated or "cancelled"
     * @param string $userId
     * @return array
     */
    public function getCarpoolsCompleted(string $userId): array
    {
        try {
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
        } catch (PDOException $e) {
            error_log("Database error in getCarpoolsCompleted() : " . $e->getMessage());
            throw new Exception("Impossible d'obtenir les covoiturages terminés");
        }
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
            error_log("Database error in cancelCarpool() : " . $e->getMessage());
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
            error_log("Error in validateCarpoolYes() : " . $e->getMessage());
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
            error_log("Error in resolveBadComment() : " . $e->getMessage());
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
            error_log("Database error in getDriverIdFromReservation() for the reservation : {$reservationId} ");
            throw new Exception("Impossible de récupérer le chauffeur de cette réservation");
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
            error_log("Database error in getReservationId() ");
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
            error_log("Database error in getTravelIdFromReservation() ");
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
            error_log("Database error in getCreditSpent() ");
            throw new Exception("Erreur lors de la récupération des crédits");
        }

        return $result['credits_spent'];
    }

    /**
     * Retrieves the list of user IDs of passengers for a given carpool (travel).
     *
     * @param string $travelId The ID of the travel
     * @return array An array of passengers (each item contains 'user_id')
     * @throws Exception If a database error occurs
     */
    public function getPassengersOfTheCarpool(string $travelId): array
    {
        try {
            $sql = 'SELECT user_id FROM reservations WHERE travel_id = :travelId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':travelId', $travelId, PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getPassengersOfTheCarpool() (travel ID: $travelId): " . $e->getMessage());
            throw new Exception("Impossible de récupérer les passagers du covoiturage");
        }
    }
    /**
     * Check if a reservation already exists for a given user and travel.
     * @param int $userId    The passenger's user ID
     * @param int $travelId  The travel (carpool) ID
     * @return bool          True if a reservation exists, false otherwise
     * @throws Exception     If a database error occurs
     */
    public function existsForUserAndTravel(string $userId, string $travelId): bool
    {
        try {
            $sql = 'SELECT COUNT(*) FROM reservations WHERE user_id = :userId AND travel_id = :travelId';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindParam(':travelId', $travelId, PDO::PARAM_STR);
            $stmt->execute();

            return (int)$stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("DB error in existsForUserAndTravel(user:$userId, travel:$travelId): " . $e->getMessage());
            throw new Exception("Impossible de vérifier l'existance de la réservation");
        }
    }

    /**
     * Get the reservations not validated of a carpool
     * @param string $travelId
     * @return array
     * @throws Exception If a database error occurs
     */
    public function getReservationsNotValidatedOfACarpool(string $travelId): array
    {
        try {
            $sql = 'SELECT * FROM reservations WHERE (is_validated = 0 OR bad_comment_validated = 0) AND travel_id = :travelId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam('travelId', $travelId, PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getReservationsNotValidatedOfACarpool() (travel ID: $travelId): " . $e->getMessage());
            throw new Exception("Impossible de récupérer les réservations non validées du covoiturage");
        }
    }

    /**
     * Retrieves reservations that include a bad comment not yet validated
     * @param int $limit Number of results to return (default: 5)
     * @param int $offset Number of results to skip (default: 0)
     * @return array Array of associative results including passenger and driver info, travel date and cities, and reservation details
     * @throws Exception If a database error occurs
     */
    public function getBadComments(int $limit = 5, int $offset = 0): array
    {
        try {
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
        } catch (PDOException $e) {
            error_log("Database error in getBadComments() : " . $e->getMessage());
            throw new Exception("Impossible de récupérer les mauvais commentaires");
        }
    }

    /**
     * Count all bad comments not yet validated
     * @return int
     * @throws Exception If a database error occurs
     */
    public function countAllBadComments(): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM reservations  WHERE bad_comment IS NOT NULL AND bad_comment_validated =0";
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return (int) $statement->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error in countAllBadComments() : " . $e->getMessage());
            throw new Exception("Impossible de compter les mauvais commentaires");
        }
    }

    /**
     * Adds credits to the specified user.
     *
     * @param string $userId The ID of the user to credit
     * @param int $creditToSend The amount of credit to add
     * @throws Exception If the update fails or no user is affected
     * @return void
     */
    private function setCreditToUser(string $userId, int $creditToSend): void
    {
        try {
            $sql = 'UPDATE users SET credit = credit + :creditToSend WHERE id = :userId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':creditToSend', $creditToSend, PDO::PARAM_INT);
            $statement->bindParam(':userId', $userId, PDO::PARAM_STR);

            if (!$statement->execute()) {
                error_log("setCreditToUser() failed to execute query for user ID $userId");
                throw new Exception("Échec de la mise à jour des crédits de l'utilisateur");
            }

            if ($statement->rowCount() === 0) {
                error_log("setCreditToUser() affected 0 rows for user ID $userId");
                throw new Exception("Aucun utilisateur trouvé avec l'ID fourni");
            }
        } catch (PDOException $e) {
            error_log("Database error in setCreditToUser() (user ID: $userId): " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour des crédits");
        }
    }


    /**
     * Set the reservation as validated
     * @param int $reservationId
     * @throws \Exception If the update fails 
     * @return void
     */
    private function markAsValidated(int $reservationId): void
    {
        try {
            $sql = 'UPDATE reservations SET is_validated = 1 WHERE id = :reservationId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            if (!$statement->execute()) {
                error_log("markAsValidated() failed to execute query for reservation ID $reservationId");
                throw new Exception("Échec de la validation du covoiturage");
            }
        } catch (PDOException $e) {
            error_log("Database error in markAsValidated() (reservation ID: $reservationId): " . $e->getMessage());
            throw new Exception("Erreur lors de la validation du covoiturage");
        }
    }

    /**
     * Stores a bad comment made by a passenger on a specific reservation.
     *
     * @param int $reservationId The ID of the reservation
     * @param string $badComment The comment content
     * @throws Exception If the update fails
     * @return void
     */
    private function addBadComment(int $reservationId, string $badComment): void
    {
        try {
            $sql = 'UPDATE reservations SET bad_comment =:badComment, bad_comment_validated = 0  WHERE id = :reservationId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':badComment', $badComment, PDO::PARAM_STR);
            $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            if (!$statement->execute()) {
                error_log("addBadComment() failed to execute query for reservation ID $reservationId");
                throw new Exception("Échec de l'enregistrement du litige");
            }
        } catch (PDOException $e) {
            error_log("Database error in addBadComment() (reservation ID: $reservationId): " . $e->getMessage());
            throw new Exception("Erreur lors l'ajout d'un mauvais commentaire");
        }
    }

    /**
     * Marks a bad comment as validated in the database.
     *
     * @param int $reservationId The reservation ID concerned by the bad comment
     * @throws Exception If the update fails or no reservation is affected
     * @return void
     */
    private function markBadCommentAsValidated(int $reservationId): void
    {
        try {
            $sql = 'UPDATE reservations SET bad_comment_validated = 1  WHERE id = :reservationId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            if (!$statement->execute()) {
                error_log("markBadCommentAsValidated() failed to execute query for reservation ID $reservationId");
                throw new Exception("Échec de la résolution du litige");
            }
        } catch (PDOException $e) {
            error_log("Database error in markBadCommentAsValidated() (reservation ID: $reservationId): " . $e->getMessage());
            throw new Exception("Erreur lors de la validation du mauvais commentaire");
        }
    }
}
