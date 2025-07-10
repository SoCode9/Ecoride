<?php

require_once __DIR__ . '/../functions.php';

class Travel
{
    private PDO $pdo;
    private string $id;
    private string $driverId;
    private ?string $travelDate;



    private ?string $travelDepartureCity;
    private ?string $travelArrivalCity;
    private ?string $travelDepartureTime;
    private ?string $travelArrivalTime;


    private ?int $travelPrice;

    private ?int $carId;
    private ?int $availableSeats;
    private ?string $travelDescription;
    private ?string $travelStatus;


    public function __construct(PDO $pdo, ?string $travelId = null)
    {
        $this->pdo = $pdo;
        //for using the searching function (in carpoolSearch page)
        if ($travelId != null) {
            $this->id = $travelId;
            $this->loadTravelFromDB();
        }
    }

    /**
     * Loads travel data from the database using the current travel ID
     * @throws \Exception If no travel is found with the given ID
     * @return void
     */
    private function loadTravelFromDB(): void
    {
        $sql = "SELECT * FROM travels WHERE id = :travel_id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':travel_id', $this->id, PDO::PARAM_STR);
        $statement->execute();
        $travelData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($travelData) {
            $this->driverId = $travelData['driver_id'];
            $this->travelDepartureCity = $travelData['travel_departure_city'];
            $this->travelArrivalCity = $travelData['travel_arrival_city'];
            $this->travelDepartureTime = $travelData['travel_departure_time'];
            $this->travelArrivalTime = $travelData['travel_arrival_time'];
            $this->travelPrice = $travelData['travel_price'];
            $this->travelDescription = $travelData['travel_description'];
            $this->carId = $travelData['car_id'];
            $this->travelStatus = $travelData['travel_status'];
        } else {
            error_log("loadTravelFromDB() failed for travel ID: {$this->id}");
            throw new Exception("Trajet introuvable");
        }
    }



    public function getIdTravel()
    {
        return $this->id;
    }

    public function getIdDriver()
    {
        return $this->driverId;
    }

    public function getCarId()
    {
        return $this->carId;
    }

    public function getDepartureCity()
    {
        return $this->travelDepartureCity;
    }

    public function getArrivalCity()
    {
        return $this->travelArrivalCity;
    }

    public function getDepartureTime()
    {
        return $this->travelDepartureTime;
    }

    public function getArrivalTime()
    {
        return $this->travelArrivalTime;
    }
    public function getPrice()
    {
        return $this->travelPrice;
    }

    public function getAvailableSeats()
    {
        return $this->availableSeats;
    }
    public function getDescription(): string|null
    {
        return $this->travelDescription;
    }

    public function getStatus()
    {
        return $this->travelStatus;
    }

    /**
     * Creates a new carpool in the database
     * @param string $driverId the id driver who add a carpool
     * @param string $travelDate the date of the carpool
     * @param string $travelDepartureCity the departure city of the carpool
     * @param string $travelArrivalCity the arrival city of the carpool
     * @param string $travelDepartureTime the departure time of the carpool
     * @param string $travelArrivalTime the arrival time of the carpool
     * @param int $travelPrice the price for each passenger of the carpool
     * @param int $carId the is car used for the carpool
     * @param string $travelComment the optionnal description of the carpool
     * @throws \Exception If a database error occurs
     * @return bool
     */
    public function createNewTravel(string $driverId, string $travelDate, string $travelDepartureCity, string $travelArrivalCity, string $travelDepartureTime, string $travelArrivalTime, int $travelPrice, int $carId, ?string $travelComment = null): void
    {
        try {
            $sql = "INSERT INTO travels (id, driver_id, travel_date,travel_departure_city, travel_arrival_city, travel_departure_time, travel_arrival_time,travel_price, car_id, travel_description) VALUES (UUID(), :driverId,:travel_date, :travel_departure_city,:travel_arrival_city,:travel_departure_time,:travel_arrival_time,:travel_price,:car_id,:travelComment)";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':driverId', $driverId, PDO::PARAM_STR);
            $statement->bindParam(':travel_date', $travelDate, PDO::PARAM_STR);
            $statement->bindParam(':travel_departure_city', $travelDepartureCity, PDO::PARAM_STR);
            $statement->bindParam(':travel_arrival_city', $travelArrivalCity, PDO::PARAM_STR);
            $statement->bindParam(':travel_departure_time', $travelDepartureTime, PDO::PARAM_STR);
            $statement->bindParam(':travel_arrival_time', $travelArrivalTime, PDO::PARAM_STR);
            $statement->bindParam(':travel_price', $travelPrice, PDO::PARAM_INT);
            $statement->bindParam(':car_id', $carId, PDO::PARAM_INT);
            $statement->bindParam(':travelComment', $travelComment, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in createNewTravel(): " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    /**
     * 
     * @param string $dateSearch //
     * @param string $departureCitySearch //departureCity searched
     * @param string $arrivalCitySearch //arrivalCity searched
     * @return array //return the array of all travels meeting the criteria
     */
    /**
     * To search for all travels that meet the criteria
     * @param mixed $dateSearch date searched
     * @param mixed $departureCitySearch departure city searched
     * @param mixed $arrivalCitySearch arrival city searched
     * @param mixed $eco filter eco searched
     * @param mixed $maxPrice filter price searched
     * @param mixed $maxDuration filter maximum duration searched
     * @param mixed $driverRating filter minimum driver rating searched
     * @throws \Exception If a database error occurs
     * @return array
     */
    public function searchTravels(?string $dateSearch = null, ?string $departureCitySearch = null, ?string $arrivalCitySearch = null, ?int $eco = null, ?int $maxPrice = null, ?int $maxDuration = null, ?float $driverRating = null): array
    {
        if (!empty($dateSearch)) {
            // Convert date from `dd.mm.yyyy` to `yyyy-mm-dd`
            $dateObject = DateTime::createFromFormat('d.m.Y', $dateSearch);
            if ($dateObject) {
                $dateSearch = $dateObject->format('Y-m-d');
            }
        }
        try {
            $sql = "SELECT travels.*, users.pseudo AS driver_pseudo, users.photo AS driver_photo, AVG(ratings.rating) AS driver_rating, driver.user_id AS driver_id,
                cars.car_electric AS car_electric, cars.car_seats_offered AS seats_offered, TIMESTAMPDIFF(MINUTE, travel_departure_time, travel_arrival_time)/60 AS travel_duration 
                FROM travels 
                JOIN users ON users.id = travels.driver_id 
                JOIN driver ON driver.user_id = travels.driver_id 
                JOIN cars ON cars.car_id = travels.car_id  
                LEFT JOIN ratings ON ratings.driver_id = driver.user_id
                WHERE (travel_date = :travel_date) AND (travel_departure_city = :departure_city) AND (travel_arrival_city = :arrival_city) AND (travel_status= 'not started')";

            if (isset($eco)) {
                $sql .= " AND (car_electric = 1)";
            }

            if (!empty($maxPrice)) {
                $sql .= " AND (travel_price <= :max_price)";
            }

            if (!empty($maxDuration)) {
                $sql .= " AND TIMESTAMPDIFF(MINUTE, travels.travel_departure_time, travels.travel_arrival_time)/60 <= :max_duration";
            }

            if (!empty($driverRating)) {
                $sql .= " GROUP BY travels.id HAVING AVG(ratings.rating) >= :driver_rating";
            } else {
                $sql .= " GROUP BY travels.id";
            }

            $sql .= " ORDER BY travel_departure_time ASC";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":travel_date", $dateSearch, PDO::PARAM_STR);
            $statement->bindParam(":departure_city", $departureCitySearch, PDO::PARAM_STR);
            $statement->bindParam(":arrival_city", $arrivalCitySearch, PDO::PARAM_STR);

            if (!empty($maxPrice)) {
                $statement->bindParam(":max_price", $maxPrice, PDO::PARAM_INT);
            }

            if (!empty($maxDuration)) {
                $statement->bindParam(":max_duration", $maxDuration, PDO::PARAM_INT);
            }

            if (!empty($driverRating)) {
                $statement->bindValue(":driver_rating", number_format($driverRating, 1, '.', ''), PDO::PARAM_STR);
            }

            $statement->execute();
            $travels = $statement->fetchAll(PDO::FETCH_ASSOC);

            //clean data with the right format
            foreach ($travels as &$travel) {
                $travel['travel_date'] = formatDate($travel['travel_date']);
                $travel['travel_departure_time'] = formatTime($travel['travel_departure_time']);
                $travel['travel_arrival_time'] = formatTime($travel['travel_arrival_time']);
            }

            return $travels;
        } catch (PDOException $e) {
            error_log("Database error in searchTravels(): " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    /**
     * To search the next date matching the criteria. return nothing if it doesn't exist
     * @param mixed $dateSearch date searched
     * @param mixed $departureCitySearch departure city searched
     * @param mixed $arrivalCitySearch arrival city searched
     * @param mixed $eco filter eco searched
     * @param mixed $maxPrice filter price searched
     * @param mixed $maxDuration filter maximum duration searched
     * @param mixed $driverRating filter minimum driver rating searched
     * @throws \Exception If a database error occurs
     * @return array
     */
    public function searchnextTravelDate(?string $dateSearch = null, ?string $departureCitySearch = null, ?string $arrivalCitySearch = null, ?int $eco = null, ?int $maxPrice = null, ?int $maxDuration = null, ?float $driverRating = null): array
    {
        try {
            $sql = "SELECT travel_date FROM travels 
        JOIN users ON users.id = travels.driver_id JOIN driver ON driver.user_id = travels.driver_id 
        JOIN cars ON cars.car_id = travels.car_id  
        LEFT JOIN ratings ON ratings.driver_id = driver.user_id  
        WHERE (travel_date > :travel_date) AND (travel_departure_city = :departure_city) AND (travel_arrival_city = :arrival_city) AND (travel_status= 'not started')";
            if (isset($eco)) {
                $sql .= " AND (car_electric = 1)";
            }

            if (!empty($maxPrice)) {
                $sql .= " AND (travel_price <= :max_price)";
            }

            if (!empty($maxDuration)) {
                $sql .= " AND TIMESTAMPDIFF(MINUTE, travels.travel_departure_time, travels.travel_arrival_time)/60 <= :max_duration";
            }

            if (!empty($driverRating)) {
                $sql .= " GROUP BY travels.id HAVING AVG(ratings.rating) >= :driver_rating";
            } else {
                $sql .= " GROUP BY travels.id";
            }

            $sql .= " ORDER BY travel_date ASC LIMIT 1"; //return the first element (=the first date matching the criteria)

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":travel_date", $dateSearch, PDO::PARAM_STR);
            $statement->bindParam(":departure_city", $departureCitySearch, PDO::PARAM_STR);
            $statement->bindParam(":arrival_city", $arrivalCitySearch, PDO::PARAM_STR);

            if (!empty($maxPrice)) {
                $statement->bindParam(":max_price", $maxPrice, PDO::PARAM_INT);
            }

            if (!empty($maxDuration)) {
                $statement->bindParam(":max_duration", $maxDuration, PDO::PARAM_INT);
            }

            if (!empty($driverRating)) {
                $statement->bindValue(":driver_rating", number_format($driverRating, 1, '.', ''), PDO::PARAM_STR);
            }

            $statement->execute();
            $nextTravelDate = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $nextTravelDate;
        } catch (PDOException $e) {
            error_log("Database error in searchnextTravelDate(): " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    /**
     * Update the travel status
     * @param string $newStatus the new statut given
     * @param string $travelId the travel id for which the status is being changed
     * @throws \Exception If a database error occurs
     * @return void
     */
    public function setTravelStatus(string $newStatus, string $travelId): void
    {
        try {
            $sql = "UPDATE travels SET travel_status = :newStatus ";
            if ($newStatus === 'ended') {
                $sql .= ", validated_at = :currentDate";
            }
            $sql .= " WHERE id = :travelId";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
            $statement->bindParam(':travelId', $travelId, PDO::PARAM_STR);
            if ($newStatus === 'ended') {
                $today = date('Y-m-d H:i:s');
                $statement->bindParam(':currentDate', $today, PDO::PARAM_STR);
            }

            $statement->execute();
        } catch (Exception $e) {
            error_log("Database error in setTravelStatus(): " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    public function getDriverId(): string
    {
        return $this->driverId;
    }

    /**
     * Calculate the difference between the departure time and the arrival time of the travel
     * @param string $travelDepartureTime
     * @param string $travelArrivalTime
     * @return string // return a duration (ex. 2h40)
     */
    public function travelDuration(string $travelDepartureTime, string $travelArrivalTime): string
    {
        // Convert string to DateTime Objects 
        $departure = new DateTime($travelDepartureTime);
        $arrival = new DateTime($travelArrivalTime);

        // difference calcul
        $interval = $departure->diff($arrival);

        return $interval->format('%hh%I');
    }

    /**
     * Get the credits earned by the Ecoride platform
     * @throws \Exception If a database error occurs
     * @return int
     */
    public function getCreditsEarned(): int
    {
        try {
            $sql = 'SELECT count(validated_at) AS carpoolsValidated FROM travels';
            $statement = $this->pdo->prepare($sql);
            $statement->execute();

            $nbCarpoolsValidated = $statement->fetch(PDO::FETCH_ASSOC);
            $creditsEarnedByPlatform = $nbCarpoolsValidated['carpoolsValidated'] * 2;
            return $creditsEarnedByPlatform;
        } catch (PDOException $e) {
            error_log("Database error in getCreditsEarned() : " . $e->getMessage());
            throw new Exception("Impossible de récupérer les crédits gagnés par la plateforme");
        }
    }


    /**
     * Get the travel date
     * @param string $idTravel the id travel 
     * @throws \Exception If a database error occurs
     * @return string
     */
    public function getDate(string $idTravel): string
    {
        try {
            $sql = "SELECT travel_date FROM travels WHERE id = :id";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":id", $idTravel, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_COLUMN);
            return $result ? htmlspecialchars($result) : "Aucune donnée trouvée.";
        } catch (PDOException $e) {
            error_log("Database error in getDate() : " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }
}
