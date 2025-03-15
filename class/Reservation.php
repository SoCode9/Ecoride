<?php

require_once "../database.php";

class Reservation
{
    private ?PDO $pdo;
    private int $reservationId;
    private int $userId;
    private ?int $travelId;

    private ?bool $isValidated;

    public function __construct($pdo, $userId, ?int $travelId=null, ?bool $isValidated=null)
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
        JOIN ratings ON ratings.driver_id = travels.driver_id
        
        WHERE (travel_status = 'in validation') AND (reservations.user_id =:userConnected_id)
        GROUP BY travels.id, users.pseudo
        ORDER BY travel_date ASC ";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(":userConnected_id", $userId, PDO::PARAM_INT);
        $statement->execute();

        $carpoolListToValidate = $statement->fetchAll();

        return $carpoolListToValidate;
    }

}