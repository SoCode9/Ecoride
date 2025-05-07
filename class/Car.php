<?php

require_once __DIR__ . '/../functions.php';


class Car
{
    private PDO $pdo;
    private ?string $driverId;
    private ?string $brand;
    private ?string $model;
    private ?string $licencePlate;
    private ?string $firstRegistrationDate;
    private ?int $seatsOffered;
    private ?bool $electric;
    private ?string $color;

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

    /**
     * Creates a new car for the specified user
     * @param string $userId
     * @param string $brandId
     * @param string $model
     * @param string $licencePlate
     * @param string $firstRegistrationDate
     * @param int $seatsOffered
     * @param bool $electric
     * @param string $color
     * @return array{car_id: bool|string, message: string, success: bool|array{error: string, success: bool}} Result with success status and message
     */
    public function createNewCar(string $userId, string $brandId, string $model, string $licencePlate, string $firstRegistrationDate, int $seatsOffered, bool $electric, string $color): array
    {
        try {
            $this->ensureDriver($userId);
            $sql = 'INSERT INTO cars (brand_id,driver_id,car_licence_plate,car_first_registration_date, car_seats_offered,car_model,car_color,car_electric) 
        VALUES (:brand_id, :driver_id, :licence_plate, :first_reg_date, :seats, :model, :color, :electric)';

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':brand_id' => $brandId,
                ':driver_id' => $userId,
                ':licence_plate' => $licencePlate,
                ':first_reg_date' => $firstRegistrationDate,
                ':seats' => $seatsOffered,
                ':model' => $model,
                ':color' => $color,
                ':electric' => $electric,
            ]);

            return [
                'success' => true,
                'message' => 'Véhicule enregistré avec succès',
                'car_id' => $this->pdo->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => "Erreur lors de la création du véhicule : " . $e->getMessage()
            ];
        }
    }

    /**
     * Delete a car
     * @param int $carId car id to delete
     * @throws \Exception
     * @return void
     */
    public function deleteCar(int $carId): void
    {
        try {
            $this->pdo->beginTransaction();
            $sql = 'DELETE FROM cars WHERE car_id = :carId';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':carId', $carId, PDO::PARAM_INT);
            $statement->execute();

            // Check if a row was actually deleted
            if ($statement->rowCount() === 0) {
                throw new Exception("Aucune voiture trouvée");
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erreur dans deleteCar : " . $e->getMessage());
            throw new Exception("Impossible de supprimer la voiture");
        }
    }

    /**
     *Loads car information from the database based on a travel ID (single car) 
     * or a driver ID (multiple cars).
     * @param string|null $travelId If provided, loads a single car associated with this travel.
     * @param string|null $driverId If provided, loads all cars associated with this driver.
     * @throws \Exception If no car is found when searching by travel ID.
     * @return void
     */
    private function loadCarFromDB(?string $travelId = null, ?string $driverId = null): void
    {
        $sql = "SELECT cars.*, brands.* FROM cars 
        JOIN driver ON driver.user_id = cars.driver_id 
        JOIN brands ON brands.id = cars.brand_id";

        $conditions = [];
        $params = [];

        // If travel ID is provided, join with travels and filter
        if (!empty($travelId)) {
            $sql .= "  JOIN travels ON travels.car_id =cars.car_id";
            $conditions[] = "travels.id = :travel_id";
            $params[':travel_id'] = $travelId;
        }

        // If driver ID is provided, filter accordingly
        if (!empty($driverId)) {
            $conditions[] = "driver.user_id = :driver_id";
            $params[':driver_id'] = $driverId;

        }

        // Apply WHERE clause if needed
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $statement = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value, PDO::PARAM_STR);
        }

        $statement->execute();

        if (!empty($travelId)) {
            // Expecting a single car result
            $carData = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$carData) {
                throw new Exception("Aucune voiture trouvée pour ce trajet");
            }

            // Populate properties for a single car
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
            // Expecting multiple cars
            $this->cars = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Ensures that the user is a driver. If not, adds them as a driver
     * and updates their role in the users table.
     * @param string $userId
     * @return void
     */
    private function ensureDriver(string $userId): void
    {
        // Check if the user is already a driver
        $statement = $this->pdo->prepare('SELECT user_id FROM driver WHERE user_id = :userId');
        $statement->execute([':userId' => $userId]);

        if (!$statement->fetch()) {
            // Add the user as a driver
            $this->pdo->prepare('INSERT INTO driver (user_id) VALUES (:userId)')
                ->execute([':userId' => $userId]);

            // Update user's role to "driver" (role_id = 2)
            $this->pdo->prepare('UPDATE users SET id_role = 2 WHERE id = :userId')
                ->execute([':userId' => $userId]);
        }
    }

    /**
     * Returns the number of seats offered by a specific car.
     * @param int $carId The ID of the car.
     * @throws \Exception If the car does not exist.
     * @return int Number of seats offered.
     */
    public function getSeatsOfferedByCar(int $carId): int
    {
        $sql = "SELECT car_seats_offered FROM cars WHERE car_id = :carId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':carId', $carId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Aucune voiture trouvée pour cet id : $carId");
        }

        return (int) $result['car_seats_offered'];
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
        return isset($this->electric) ? (bool) $this->electric : null;
    }
}