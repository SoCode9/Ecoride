<?php
require_once "../database.php";

class Driver extends User
{

    protected int $id;
    private float $rating;

    public function __construct(PDO $pdo, int $driverId)
    {
        parent::__construct($pdo, $driverId); // Charge les données User
        $this->loadDriverFromDB();
    }

    private function loadDriverFromDB()
    {

        $sql = "SELECT driver.*,users.* FROM driver JOIN users ON driver.user_id = users.id 
        WHERE driver.user_id = :driver_id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':driver_id', $this->id, PDO::PARAM_INT);
        $statement->execute();

        $driverData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($driverData) {
             // Informations héritées de User
            $this->rating = (float) $driverData['driver_note'];
             // Informations héritées de User
            $this->pseudo = $driverData['pseudo'];
            $this->mail = $driverData['mail'];
        } else {
            throw new Exception("Conducteur introuvable pour l'id {$this->id}.");
        }
    }

    public function getRating()
    {
        return $this->rating;
    }
}