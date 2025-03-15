<?php

require_once '../common.php';


class Car
{

    private $pdo;
    private $driverId;
    private $brand;
    private $model;
    private string $licencePlate;
    private string $firstRegistrationDate;
    private int $seatsOffered;
    private bool $electric;
    private $color;
    public function __construct(PDO $pdo, ?int $driverId = null, ?int $travelId = null)
    {
        $this->pdo = $pdo;
        $this->loadCarFromDB($travelId, $driverId);
    }

    private function loadCarFromDB(?int $travelId = null, ?int $driverId = null)
    {
        $sql = "SELECT cars.*, brands.* FROM cars 
        JOIN driver ON driver.user_id = cars.driver_id 
        JOIN travels ON travels.car_id =cars.car_id  
        JOIN brands ON brands.id = cars.brand_id";

        $conditions = [];
        $params = [];

        if (!empty($travelId)) {
            $conditions[] = "travels.id = :travel_id";
            $params[':travel_id'] = $travelId;

        }
        if (!empty($driverId)) {
            $conditions[] = "driver.user_id = :driver_id";
            $params[':driver_id'] = $driverId;

        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $statement = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value, PDO::PARAM_INT);
        }

        $statement->execute();

        $carData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($carData) {
            $this->driverId = $carData['driver_id'];
            $this->brand = $carData['name'];
            $this->model = $carData['car_model'];
            $this->licencePlate = $carData['car_licence_plate'];
            $this->firstRegistrationDate = $carData['car_first_registration_date'];
            $this->seatsOffered = $carData['car_seats_offered'];
            $this->electric = $carData['car_electric'];
            $this->color = $carData['car_color'];
        } else {
            throw new Exception("Aucune voiture trouvée pour ce trajet ou ce chauffeur");
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