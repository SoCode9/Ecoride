<?php

require_once '../common.php';

//pour chaque trajet proposé

class Travel
{
    private PDO $pdo;

    //faire lien avec le chauffeur qui poste le trajet
    //private int $driver_id;
    private ?string $travelDate;

    private ?string $travelDepartureCity; 
    private ?string $travelArrivalCity;
    private ?string $travelDepartureTime;
    private ?string $travelArrivalTime;


    private ?int $travelPrice;

    private ?int $placesOffered;

    private ?int $carId;


    public function __construct(PDO $pdo, string $travelDate = null, string $travelDepartureCity = null, string $travelArrivalCity = null, $travelDepartureTime = null, $travelArrivalTime = null, int $travelPrice = null, int $placesOffered = null, int $carId = null)
    {
        //add id_driver ?
        //global $pdo;
        $this->pdo = $pdo;
        $this->travelDate = $travelDate;
        $this->travelDepartureCity = $travelDepartureCity;
        $this->travelArrivalCity = $travelArrivalCity;
        $this->travelDepartureTime = $travelDepartureTime;
        $this->travelArrivalTime = $travelArrivalTime;
        $this->travelPrice = $travelPrice;
        $this->placesOffered = $placesOffered;
        $this->carId = $carId;

    }

    public function saveTravelToDatabase()
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO travels (travel_date,travel_departure_city, travel_arrival_city, travel_departure_time, travel_arrival_time,travel_price,places_offered, car_id) VALUES (:travel_date, :travel_departure_city,:travel_arrival_city,:travel_departure_time,:travel_arrival_time,:travel_price,:places_offered ,:car_id)");
            return $stmt->execute([
                ':travel_date' => $this->travelDate,
                ':travel_departure_city' => $this->travelDepartureCity,
                ':travel_arrival_city' => $this->travelArrivalCity,
                ':travel_departure_time' => $this->travelDepartureTime,
                ':travel_arrival_time' => $this->travelArrivalTime,
                ':travel_price' => $this->travelPrice,
                ':places_offered' => $this->placesOffered,
                ':car_id' => $this->carId
            ]);

        } catch (PDOException $e) {
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }

    }

    public function displayTravelsBrut(string $sql, string $column = null)
    {
        // Vérifier si la connexion à la base de données est bien établie
        if (!$this->pdo) {
            die("<p style='color: red;'>Erreur : Connexion à la base de données non disponible.</p>");
        }

        // Récupérer les trajets
        $statement = $this->pdo->prepare($sql);
        if ($statement->execute()) {
            $travels = $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "<p style='color: red;'>Erreur lors de l'exécution de la requête SQL.</p>";
            exit();
        }

        // Si aucune colonne spécifique n'est demandée, afficher tout
        if ($column === null) {
            echo "<pre>";
            print_r($travels);
            echo "</pre>";
            return;
        }

        //  Afficher seulement la colonne demandée
        echo "<h2>Affichage de la colonne : $column</h2>";
        echo "<ul>";
        foreach ($travels as $t) {
            if (isset($t[$column])) {
                echo "<li>" . htmlspecialchars($t[$column]) . "</li>";
            } else {
                echo "<li style='color: red;'>Erreur : La colonne '$column' n'existe pas.</li>";
            }
        }
        echo "</ul>";

    }

    public function getDate(int $idTravel)
    {
        $sql = "SELECT travel_date FROM travels WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $idTravel, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_COLUMN);
            return $result ? htmlspecialchars($result) : "Aucune donnée trouvée.";
        } else {
            return "Erreur lors de l'exécution de la requête.";
        }
    }


    /**
     * Ne sert pour l'instant à rien -> je l'intègre qqe part ?
     * @return array return an array
     */
    public function allFuturesTravels(): array
    { 
        if (!$this->pdo) {
            die("<p style='color: red;'>Erreur : Connexion à la base de données non disponible.</p>");
        }
        //request for all futures travels 
        //#### ADD WHERE driver_id is not mine ####
        $sql = "SELECT * FROM travels WHERE travel_date >= CURDATE() ";

        $statement = $this->pdo->prepare($sql);
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
        /*  test pour afficher les voyages
         echo "<pre>"; 
         print_r($travels);
         echo "</pre>"; */

        return $travels;
    }

    /**
     * To search for all travels that meet the criteria
     * @param string $dateSearch //date searched
     * @param string $departureCitySearch //departureCity searched
     * @param string $arrivalCitySearch //arrivalCity searched
     * @return array //return the array of all travels meeting the criteria
     */
    public function searchTravels(string $dateSearch, string $departureCitySearch, string $arrivalCitySearch): array
    {
        if (!$this->pdo) {
            die("<p style='color: red;'>Erreur : Connexion à la base de données non disponible.</p>");
        }
        // Convertir la date de `dd.mm.yyyy` à `yyyy-mm-dd`
        $dateObject = DateTime::createFromFormat('d.m.Y', $dateSearch);
        if ($dateObject) {
            $dateSearch = $dateObject->format('Y-m-d'); // Format SQL
        }

        $sql = "SELECT travels.*, users.pseudo AS driver_pseudo, driver.driver_note AS driver_note, cars.car_electric AS car_electric FROM travels JOIN users ON users.id = travels.driver_id JOIN driver ON driver.user_id = travels.driver_id JOIN cars ON cars.car_id = travels.car_id  WHERE (travel_date = :travel_date) AND (travel_departure_city = :departure_city) AND (travel_arrival_city = :arrival_city) ORDER BY travel_departure_time ASC";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(":travel_date", $dateSearch, PDO::PARAM_STR);
        $statement->bindParam(":departure_city", $departureCitySearch, PDO::PARAM_STR);
        $statement->bindParam(":arrival_city", $arrivalCitySearch, PDO::PARAM_STR);
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

        // test pour afficher les voyages
        /* echo "<pre>";
        print_r($travels);
        echo "</pre>"; */
        return $travels;
    }

    public function getDriverPseudo($driver_id, $pdo)
    {
        $sql = "SELECT users.pseudo FROM users JOIN travels ON users.id = travels.driver_id WHERE travels.driver_id = :driver_id LIMIT 1";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':driver_id', $driver_id, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['pseudo'] : null;
    }

}