<?php

require_once "User.php";

class Driver extends User
{

    protected ?string $id;
    private ?bool $petPreference;
    private ?bool $smokerPreference;
    private ?bool $musicPreference;
    private ?bool $speakerPreference;
    private ?bool $foodPreference;
    private ?string $addPref1;
    private ?string $addPref2;
    private ?string $addPref3;


    public function __construct(PDO $pdo, string $driverId)
    {
        parent::__construct($pdo);
        $this->id = $driverId;
        $this->loadUserFromDB();
        $this->loadDriverFromDB();
    }

    /**
     * Loads driver data from the database using the current user ID
     * @throws \Exception If no driver is found with the given ID
     * @return void
     */
    private function loadDriverFromDB()
    {

        $sql = "SELECT driver.*,users.pseudo FROM driver JOIN users ON driver.user_id = users.id 
        WHERE driver.user_id = :driver_id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':driver_id', $this->id, PDO::PARAM_STR);
        $statement->execute();

        $driverData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($driverData) {
            // special Driver information
            $this->petPreference = $driverData['pets'];
            $this->smokerPreference = $driverData['smoker'];
            $this->musicPreference = $driverData['music'];
            $this->speakerPreference = $driverData['speaker'];
            $this->foodPreference = $driverData['food'];
            $this->addPref1 = $driverData['add_pref_1'];
            $this->addPref2 = $driverData['add_pref_2'];
            $this->addPref3 = $driverData['add_pref_3'];
        } else {
            error_log("loadDriverFromDB() failed for user ID: {$this->id}");
            throw new Exception("Une erreur est survenue lors du chargement des informations de l'utilisateur");
        }
    }

    /**
     * Loads all validated ratings for the current driver, including the rater's pseudo and photo.
     *
     * @throws Exception If the driver ID is not set or the query fails
     * @return array An array of ratings with user information
     */
    public function loadValidatedRatings(): array
    {
        if (empty($this->id)) {
            throw new Exception("Impossible de charger les avis sans identifiant conducteur");
        }

        try {
            $sql = "
            SELECT ratings.*, users.pseudo, users.photo
            FROM ratings
            JOIN driver ON driver.user_id = ratings.driver_id
            JOIN users ON users.id = ratings.user_id
            WHERE ratings.driver_id = :driver_id AND ratings.status = 'validated'
            ORDER BY ratings.created_at DESC
        ";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':driver_id', $this->id, PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in loadValidatedRatings() (driver ID: {$this->id}) : " . $e->getMessage());
            throw new Exception("Impossible de charger les évaluations du conducteur");
        }
    }

    /**
     * Loads the custom preferences of the current driver.
     *
     * @throws Exception If the user ID is not set or the preferences cannot be loaded
     * @return array Associative array with keys: add_pref_1, add_pref_2, add_pref_3
     */
    public function loadCustomPreferences(): array
    {
        if (empty($this->id)) {
            error_log("loadCustomPreferences() failed: driver ID is empty");
            throw new Exception("Impossible de charger les préférences sans identifiant utilisateur");
        }

        try {
            $sql = 'SELECT add_pref_1, add_pref_2, add_pref_3 FROM driver WHERE user_id = :driver_id';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':driver_id', $this->id, PDO::PARAM_STR);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in loadCustomPreferences() (user ID: {$this->id}) : " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    /**
     * Adds a new custom preference to the first available (null or empty) slot
     * among the three custom preference fields (add_pref_1, add_pref_2, add_pref_3)
     * @param string $customPrefToAdd The new preference to insert
     * @throws Exception If the user ID is not set or a database error occurs
     * @return void
     */
    public function addCustomPreference(string $customPrefToAdd): void
    {
        if (empty($this->id)) {
            error_log("addCustomPreference() failed: user ID is empty.");
            throw new Exception("Impossible d'ajouter une préférence sans identifiant utilisateur");
        }

        $customPreferencesInDB = $this->loadCustomPreferences();
        try {
            foreach ($customPreferencesInDB as $columnName => $value) {
                if ($value === null) {
                    $sql = "UPDATE driver SET $columnName = :customPrefToAdd WHERE user_id = :driver_id";
                    $statement = $this->pdo->prepare($sql);
                    $statement->bindParam(':customPrefToAdd', $customPrefToAdd, PDO::PARAM_STR);
                    $statement->bindParam(':driver_id', $this->id, PDO::PARAM_STR);
                    $statement->execute();
                    return;
                }
            }
        } catch (PDOException $e) {
            error_log("Database error in addCustomPreference() (user ID: {$this->id}) : " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    public function getPetPreference(): ?bool
    {
        return $this->petPreference;
    }

    public function getSmokerPreference(): ?bool
    {
        return $this->smokerPreference;
    }
    public function getMusicPreference(): ?bool
    {
        return $this->musicPreference;
    }
    public function getSpeakerPreference(): ?bool
    {
        return $this->speakerPreference;
    }
    public function getFoodPreference(): ?bool
    {
        return $this->foodPreference;
    }

    public function getAddPref1(): ?string
    {
        return $this->addPref1;
    }


    public function getAddPref2(): ?string
    {
        return $this->addPref2;
    }


    public function getAddPref3(): ?string
    {
        return $this->addPref3;
    }

    /**
     * Calculates the average rating for the driver based on validated ratings
     * @return float|null The average rating (e.g., 4.2), or null if no rating is available
     */
    public function getAverageRatings(): ?float
    {
        $allInfoRatings = $this->loadValidatedRatings();
        if (empty($allInfoRatings)) {
            return null; // if the driver has no rating
        }
        $average = array_sum(array_column($allInfoRatings, 'rating')) / count($allInfoRatings);
        return round($average, 1);
    }

    /**
     * Returns the number of validated ratings received by the driver
     * @return int The total number of ratings (0 if none)
     */
    public function countRatings(): int
    {
        $allInfoRatings = $this->loadValidatedRatings();
        return $allInfoRatings ? count($allInfoRatings) : 0;
    }



    public function setSmokerPreference($preference)
    {
        $this->updateDriverPreference('smoker', $preference);
    }

    public function setPetPreference($preference)
    {
        $this->updateDriverPreference('pets', $preference);
    }

    public function setFoodPreference($preference)
    {
        $this->updateDriverPreference('food', $preference);
    }

    public function setSpeakPreference($preference)
    {
        $this->updateDriverPreference('speaker', $preference);
    }
    public function setMusicPreference($preference)
    {
        $this->updateDriverPreference('music', $preference);

    }

    private function updateDriverPreference(string $column, $value): void
    {
        if (empty($this->id)) {
            throw new Exception("Impossible de modifier la préférence sans identifiant utilisateur");
        }

        try {
            $sql = "UPDATE driver SET $column = :value WHERE user_id = :userId";
            $statement = $this->pdo->prepare($sql);
            if ($value === null) {
                $statement->bindValue(':value', null, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':value', $value, PDO::PARAM_INT);
            }
            $statement->bindParam(':userId', $this->id, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in updateDriverPreference() [$column] (user ID: {$this->id}) : " . $e->getMessage());
            throw new Exception("Impossible d'ajouter la préférence");
        }
    }
}