<?php

require_once __DIR__ . '/../functions.php';


class Car
{

    private $pdo;
    private string|null $driverId;
    private string $brand;
    private string $model;
    private string $licencePlate;
    private string $firstRegistrationDate;
    private int $seatsOffered;
    private bool $electric;
    private string $color;

    private ?array $car = null; //if the search is by travelID
    public array $cars = []; //if the search is by driverId. A driver can have one or many cars

    /**
     * to initialize one or many car(s), one of the parameters must be null
     * @param PDO $pdo
     * @param mixed $driverId
     * @param mixed $travelId
     */
    public function __construct(PDO $pdo, ?string $driverId = null, ?string $travelId = null)
    {
        $this->pdo = $pdo;

        $this->loadCarFromDB($travelId, $driverId);

    }

    public function createCar(PDO $pdo, string $userId, string $brandId, string $model, string $licencePlate, string $firstRegistrationDate, int $seatsOffered, bool $electric, string $color)
    {
        // Vérifier si l'utilisateur est déjà un driver
        $sqlCheckDriver = 'SELECT user_id FROM driver WHERE user_id = :userId';
        $stmtCheckDriver = $pdo->prepare($sqlCheckDriver);
        $stmtCheckDriver->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmtCheckDriver->execute();

        if (!$stmtCheckDriver->fetch()) {
            // L'utilisateur n'est pas encore un driver, donc on l'ajoute
            $sqlInsertDriver = 'INSERT INTO driver (user_id) VALUES (:userId)';
            $stmtInsertDriver = $pdo->prepare($sqlInsertDriver);
            $stmtInsertDriver->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmtInsertDriver->execute();

            //et on ajoute le rôle driver à l'utilisateur, 2 par défaut
            $sqlUpdateTypeUser = 'UPDATE users SET id_role = 2 WHERE id = :userId';
            $stmtUpdateTypeUser = $pdo->prepare($sqlUpdateTypeUser);
            $stmtUpdateTypeUser->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmtUpdateTypeUser->execute();
        }

        $sql = 'INSERT INTO cars (brand_id,driver_id,car_licence_plate,car_first_registration_date, car_seats_offered,car_model,car_color,car_electric) 
        VALUES (:brand_id, :driver_id, :car_licence_plate, :car_first_registration_date, :car_seats_offered, :car_model, :car_color, :car_electric)';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':brand_id', $brandId, PDO::PARAM_INT);
        $statement->bindParam(':driver_id', $userId, PDO::PARAM_STR);
        $statement->bindParam(':car_licence_plate', $licencePlate, PDO::PARAM_STR);
        $statement->bindParam(':car_first_registration_date', $firstRegistrationDate, PDO::PARAM_STR);
        $statement->bindParam(':car_seats_offered', $seatsOffered, PDO::PARAM_INT);
        $statement->bindParam(':car_model', $model, PDO::PARAM_STR);
        $statement->bindParam(':car_color', $color, PDO::PARAM_STR);
        $statement->bindParam(':car_electric', $electric, PDO::PARAM_BOOL);
        $statement->execute();
    }

    /**
     * function launched automatically when new Car is initialized
     * @param mixed $travelId if the search is by travelId (only one car identifier found) -> you can use getters and setters
     * @param mixed $driverId if search by driverId (can have several cars) --> can't use getters and setters but a foreach and ['field_db_name'].
     * @throws \Exception
     * @return void
     */
    private function loadCarFromDB(?string $travelId = null, ?string $driverId = null)
    {
        $sql = "SELECT cars.*, brands.* FROM cars 
        JOIN driver ON driver.user_id = cars.driver_id 
        JOIN brands ON brands.id = cars.brand_id";

        $conditions = [];
        $params = [];

        if (!empty($travelId)) {
            $sql .= "  JOIN travels ON travels.car_id =cars.car_id";
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
            $statement->bindValue($key, $value, PDO::PARAM_STR);
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

        }
    }

    public function nbSeatsOfferedInACarpool($pdo, $carId)
    {
        $sql = "SELECT car_seats_offered AS 'seats_offered' FROM cars WHERE car_id = :carId";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':carId', $carId, PDO::PARAM_INT);
        $statement->execute();
        $nbSeatsOfferedInACarpool = $statement->fetch();
        return $nbSeatsOfferedInACarpool['seats_offered'];
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