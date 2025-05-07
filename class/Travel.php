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
    private int $availableSeats;
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
            throw new Exception("Trajet introuvable.");
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

    public function saveTravelToDatabase($pdo, $driverId, $travelDate, $travelDepartureCity, $travelArrivalCity, $travelDepartureTime, $travelArrivalTime, $travelPrice, $carId, $travelComment)
    {
        try {
            $sql = "INSERT INTO travels (id, driver_id, travel_date,travel_departure_city, travel_arrival_city, travel_departure_time, travel_arrival_time,travel_price, car_id, travel_description) VALUES (UUID(), :driverId,:travel_date, :travel_departure_city,:travel_arrival_city,:travel_departure_time,:travel_arrival_time,:travel_price,:car_id,:travelComment)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':driverId', $driverId, PDO::PARAM_STR);
            $statement->bindParam(':travel_date', $travelDate, PDO::PARAM_STR);
            $statement->bindParam(':travel_departure_city', $travelDepartureCity, PDO::PARAM_STR);
            $statement->bindParam(':travel_arrival_city', $travelArrivalCity, PDO::PARAM_STR);
            $statement->bindParam(':travel_departure_time', $travelDepartureTime, PDO::PARAM_STR);
            $statement->bindParam(':travel_arrival_time', $travelArrivalTime, PDO::PARAM_STR);
            $statement->bindParam(':travel_price', $travelPrice, PDO::PARAM_INT);
            $statement->bindParam(':car_id', $carId, PDO::PARAM_INT);
            $statement->bindParam(':travelComment', $travelComment, PDO::PARAM_STR);
            return $statement->execute();

        } catch (PDOException $e) {
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }

    }

    /**
     * To search for all travels that meet the criteria
     * @param string $dateSearch //date searched
     * @param string $departureCitySearch //departureCity searched
     * @param string $arrivalCitySearch //arrivalCity searched
     * @return array //return the array of all travels meeting the criteria
     */
    public function searchTravels(?string $dateSearch = null, ?string $departureCitySearch = null, ?string $arrivalCitySearch = null, ?int $eco = null, ?int $maxPrice = null, ?int $maxDuration = null, ?float $driverRating = null): array
    {
        if (!$this->pdo) {
            die("<p style='color: red;'>Erreur : Connexion à la base de données non disponible.</p>");
        }
        if (!empty($dateSearch)) {
            // Convert date from `dd.mm.yyyy` to `yyyy-mm-dd`
            $dateObject = DateTime::createFromFormat('d.m.Y', $dateSearch);
            if ($dateObject) {
                $dateSearch = $dateObject->format('Y-m-d'); // Format SQL
            }
        }

        $sql = "SELECT travels.*, users.pseudo AS driver_pseudo, users.photo AS driver_photo, AVG(ratings.rating) AS driver_rating, driver.user_id AS driver_id,
        cars.car_electric AS car_electric, cars.car_seats_offered AS seats_offered, TIMESTAMPDIFF(MINUTE, travel_departure_time, travel_arrival_time)/60 AS travel_duration 
        FROM travels 
        JOIN users ON users.id = travels.driver_id 
        JOIN driver ON driver.user_id = travels.driver_id 
        JOIN cars ON cars.car_id = travels.car_id  
        LEFT JOIN ratings ON ratings.driver_id = driver.user_id  -- Lier la table des notes
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
            $sql .= " GROUP BY travels.id HAVING AVG(ratings.rating) >= :driver_rating";  // Ajout du filtre sur la moyenne
        } else {
            $sql .= " GROUP BY travels.id";  // On groupe toujours par trajet
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

        if ($statement->execute()) {
            $travels = $statement->fetchAll(PDO::FETCH_ASSOC);

            //clean data with the right format
            foreach ($travels as &$travel) {
                $travel['travel_date'] = formatDate($travel['travel_date']);
                $travel['travel_departure_time'] = formatTime($travel['travel_departure_time']);
                $travel['travel_arrival_time'] = formatTime($travel['travel_arrival_time']);
            }

        } else {
            echo "<p style='color: red;'>Erreur lors de l'exécution de la requête SQL.</p>";
            exit();
        }

        return $travels;
    }


    /**
     * if no travel is found with the date, return the next date matching the criteria. return nothing if it doesn't exist.
     * @param string $dateSearch // calculate the next date from this one
     * @param string $departureCitySearch
     * @param string $arrivalCitySearch
     * @param mixed $eco //if selected
     * @param mixed $maxPrice //if selected
     * @param mixed $maxDuration //if selected
     * @param mixed $driverRating //if selected
     * @return array //return only one date, the earliest
     */
    public function searchnextTravelDate(?string $dateSearch = null, ?string $departureCitySearch = null, ?string $arrivalCitySearch = null, ?int $eco = null, ?int $maxPrice = null, ?int $maxDuration = null, ?float $driverRating = null): array
    {
        $sql = "SELECT travel_date FROM travels 
        JOIN users ON users.id = travels.driver_id JOIN driver ON driver.user_id = travels.driver_id 
        JOIN cars ON cars.car_id = travels.car_id  
        LEFT JOIN ratings ON ratings.driver_id = driver.user_id  -- Lier la table des notes
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
            $sql .= " GROUP BY travels.id HAVING AVG(ratings.rating) >= :driver_rating";  // Ajout du filtre sur la moyenne
        } else {
            $sql .= " GROUP BY travels.id";  // On groupe toujours par trajet
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

        if ($statement->execute()) {
            $nextTravelDate = $statement->fetchAll(PDO::FETCH_ASSOC);


        } else {
            echo "<p style='color: red;'>Erreur lors de l'exécution de la requête SQL.</p>";
            exit();
        }

        return $nextTravelDate;
    }

    public function setTravelStatus($newStatus, $travelId)
    {
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

        try {
            $statement->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour du statut du trajet : " . $e->getMessage());
        }
    }

    public function getDriverId()
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

    public function getCreditsEarned()
    {
        $sql = 'SELECT count(validated_at) AS carpoolsValidated FROM travels';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $nbCarpoolsValidated = $statement->fetch(PDO::FETCH_ASSOC);
        $creditsEarnedByPlatform = $nbCarpoolsValidated['carpoolsValidated'] * 2;
        return $creditsEarnedByPlatform;
    }



    public function getDate(string $idTravel)
    {
        $sql = "SELECT travel_date FROM travels WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(":id", $idTravel, PDO::PARAM_STR);
        if ($statement->execute()) {
            $result = $statement->fetch(PDO::FETCH_COLUMN);
            return $result ? htmlspecialchars($result) : "Aucune donnée trouvée.";
        } else {
            return "Erreur lors de l'exécution de la requête.";
        }
    }
}