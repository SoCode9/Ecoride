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

    private ?array $car = null; //if the search is by travelID
    private array $cars = []; //if the search is by driverId. A driver can have one or many cars

    /**
     * to initialize one or many car(s), one of the parameters must be null
     * @param PDO $pdo
     * @param mixed $driverId
     * @param mixed $travelId
     */
    public function __construct(PDO $pdo, ?int $driverId = null, ?int $travelId = null)
    {
        $this->pdo = $pdo;
        $this->loadCarFromDB($travelId, $driverId);
    }

    /**
     * function launched automatically when new Car is initialized
     * @param mixed $travelId if the search is by travelId (only one car identifier found) -> you can use getters and setters
     * @param mixed $driverId if search by driverId (can have several cars) --> can't use getters and setters but a foreach and ['field_db_name'].
     * @throws \Exception
     * @return void
     */
    private function loadCarFromDB(?int $travelId = null, ?int $driverId = null)
    {
        $sql = "SELECT cars.*, brands.* FROM cars 
        JOIN driver ON driver.user_id = cars.driver_id 
        JOIN brands ON brands.id = cars.brand_id";  

        $conditions = [];
        $params = [];

        if (!empty($travelId)) {
            $sql.= "  JOIN travels ON travels.car_id =cars.car_id";
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

        if (!empty($travelId)) {
            //if search by travelId (one car id founed) -> can use getters and setters

            $carData = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$carData) {
                throw new Exception("Aucune voiture trouvée pour ce trajet.");
            }

            // Stock unique car
            $this->car = $carData;

            $this->driverId = $carData['driver_id'];
            $this->brand = $carData['name'];
            $this->model = $carData['car_model'];
            $this->licencePlate = $carData['car_licence_plate'];
            $this->firstRegistrationDate = $carData['car_first_registration_date'];
            $this->seatsOffered = $carData['car_seats_offered'];
            $this->electric = $carData['car_electric'];
            $this->color = $carData['car_color'];

        } elseif (!empty($driverId)) {
            // if search by driverId(can have many cars) --> cannot use getters and setters but a foreach and ['field_db_name']

            $this->cars = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (!$this->cars) {
                throw new Exception("Aucune voiture trouvée pour ce chauffeur.");
            }
        }
    }

    public function getCars(): array
    {
        return $this->cars ?? [];
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