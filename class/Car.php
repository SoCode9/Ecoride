<?php

require_once '../common.php';


class Car
{

    private $pdo;
    private $driverId;
    private $brand;
    private $model;
    private bool $electric;
    private $color;
    public function __construct(PDO $pdo, int $travelId)
    {
        $this->pdo = $pdo;
        $this->loadCarFromDB($travelId);
    }

    private function loadCarFromDB(int $travelId)
    {
        $sql = "SELECT cars.*, brand.* FROM cars 
        JOIN driver ON driver.user_id = cars.driver_id 
        JOIN travels ON travels.car_id =cars.car_id  
        JOIN brand ON brand.id = cars.brand_id
        WHERE travels.id LIKE :travel_id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':travel_id', $travelId, PDO::PARAM_INT);
        $statement->execute();

        $carData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($carData) {
            $this->driverId = $carData['driver_id'];
            $this->brand = $carData['name'];
            $this->model = $carData['car_model'];
            $this->electric = $carData['car_electric'];
            $this->color = $carData['car_color'];
        } else {
            throw new Exception("Aucune voiture trouvée pour ce trajet");
        }
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }
    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getElectric(): ?bool
    {
        return $this->electric;
    }

}