<?php

require_once "../database.php";

class Reservation
{
    private ?PDO $pdo;
    private int $reservationId;
    private int $userId;
    private ?int $travelId;

    private ?bool $isValidated;
    private ?int $creditSpent;

    public function __construct($pdo, $userId, ?int $travelId = null, ?bool $isValidated = null)
    {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->travelId = $travelId;
        $this->isValidated = $isValidated;
    }

    public function carpoolFinishedToValidate($pdo, $userId)
    {
        $sql = "SELECT travels.*, users.pseudo, ratings.rating FROM travels 
        JOIN reservations ON reservations.travel_id = travels.id 
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        
        WHERE (travel_status = 'in validation') AND (reservations.user_id =:userConnected_id)
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
        $sql = "SELECT travels.*, users.pseudo, ratings.rating FROM travels 
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
        $sql = "SELECT travels.*, users.pseudo, ratings.rating FROM travels 
        LEFT JOIN reservations ON reservations.travel_id = travels.id 
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN users ON users.id = travels.driver_id
        LEFT JOIN ratings ON ratings.driver_id = travels.driver_id
        
        WHERE (travel_status = 'ended') AND ((reservations.user_id =:userConnected_id)OR (driver.user_id = :user_connected_id))
        GROUP BY travels.id
        ORDER BY travel_date ASC ";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(":userConnected_id", $userId, PDO::PARAM_INT);
        $statement->bindParam(":user_connected_id", $userId, PDO::PARAM_INT);
        $statement->execute();

        $carpoolListFinishedAndValidated = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $carpoolListFinishedAndValidated;
    }

    public function cancelCarpool($pdo, $userId, $travelId)
    {
        $creditSpentOnTheReservation = $this->getCreditSpent($pdo, $userId, $travelId);

        $this->setCreditToUser($pdo, $userId, $creditSpentOnTheReservation);

        $sql = 'DELETE FROM reservations WHERE user_id = :userId AND travel_id = :travelId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
        try {
            $statement->execute();

        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    private function getCreditSpent($pdo, $userId, $travelId)
    {
        $sql = 'SELECT credits_spent FROM reservations WHERE user_id = :userId AND travel_id = :travelId';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':travelId', $travelId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $creditSpentOnTheReservation = $statement->fetch(PDO::FETCH_ASSOC);
            return $creditSpentOnTheReservation['credits_spent'];
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
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
            echo "Erreur : " . $e->getMessage();
        }

    }
}