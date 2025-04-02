<?php
require_once "../database.php";
require_once "User.php";

class Driver extends User
{

    protected ?int $id;
    private bool|null $petPreference;
    private bool|null $smokerPreference;
    private bool|null $musicPreference;
    private bool|null $speakerPreference;
    private bool|null $foodPreference;
    private string|null $addPref1;
    private string|null $addPref2;
    private string|null $addPref3;


    public function __construct(PDO $pdo, int $driverId)
    {
        parent::__construct($pdo, $driverId); // Charge les données User
        $this->loadDriverFromDB();
        //$this->loadDriversRatings();
    }

    /**
     * Load all informations about the selected driver, in driver's table
     * @throws \Exception if the driverId doesn't exist
     * @return void
     */
    private function loadDriverFromDB()
    {

        $sql = "SELECT driver.*,users.pseudo FROM driver JOIN users ON driver.user_id = users.id 
        WHERE driver.user_id = :driver_id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':driver_id', $this->id, PDO::PARAM_INT);
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
            // Informations inherited from User
            $this->pseudo = $driverData['pseudo'];

        } else {
            throw new Exception("Conducteur introuvable pour l'id {$this->id}.");
        }
    }

    /**
     * Select all informations about the ratings table + user's pseudo
     * @return array
     */
    public function loadDriversRatingsInformations()
    {
        $sql = "SELECT ratings.*, users.pseudo FROM ratings JOIN driver ON driver.user_id = ratings.driver_id JOIN users ON users.id = ratings.user_id
        WHERE ratings.driver_id=:driver_id AND status='validated' ORDER BY created_at DESC";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':driver_id', $this->id, PDO::PARAM_INT);
        $statement->execute();

        $ratingsData = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $ratingsData;
    }

    public function addCustomPreference($pdo, $driverId, $customPrefToAdd)
    {
        $customPreferencesInDB = $this->loadCustomPreferences($pdo, $driverId);
        foreach ($customPreferencesInDB as $columnName => $value) {
            if ($value === null) {
                $sql = "UPDATE driver SET $columnName = :customPrefToAdd WHERE user_id = :driver_id";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':customPrefToAdd', $customPrefToAdd, PDO::PARAM_STR);
                $statement->bindParam(':driver_id', $driverId, PDO::PARAM_INT);

                $statement->execute();
                return; // On sort de la fonction après la mise à jour
            }

        }
    }


    /**
     * To load all customized preferences of one user
     * @param mixed $pdo
     * @param mixed $driverId // = userId
     */
    public function loadCustomPreferences($pdo, $driverId)
    {
        $sql = 'SELECT add_pref_1, add_pref_2, add_pref_3 FROM driver WHERE user_id = :driver_id';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':driver_id', $driverId, PDO::PARAM_INT);
        $statement->execute();

        $customPreferencesInDB = $statement->fetch(PDO::FETCH_ASSOC);
        return $customPreferencesInDB;
    }

    /**
     * To give ratings average (ex. 4.2)
     * @return float|null //if average = 0 -> null
     */
    public function getAverageRatings()
    {
        $allInfoRatings = $this->loadDriversRatingsInformations();
        if (empty($allInfoRatings)) {
            return null; // if the driver has no rating
        }

        return array_sum(array_column($allInfoRatings, 'rating')) / count($allInfoRatings);

    }

    /**
     * To display the number of ratings for this driver (ex. 4)
     * @return int
     */
    public function getNbRatings()
    {
        $allInfoRatings = $this->loadDriversRatingsInformations();
        return $allInfoRatings ? count($allInfoRatings) : 0;
    }


    public function getPetPreference()
    {
        return $this->petPreference;
    }

    public function getSmokerPreference()
    {
        return $this->smokerPreference;
    }
    public function getMusicPreference()
    {
        return $this->musicPreference;
    }
    public function getSpeakerPreference()
    {
        return $this->speakerPreference;
    }
    public function getFoodPreference()
    {
        return $this->foodPreference;
    }

    public function getAddPref1()
    {
        return $this->addPref1;
    }


    public function getAddPref2()
    {
        return $this->addPref2;
    }


    public function getAddPref3()
    {
        return $this->addPref3;
    }


    public function setSmokerPreference($preference)
    {
        $sql = "UPDATE driver SET smoker = :preference WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        if ($preference === null) {
            $stmt->bindValue(':preference', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':preference', $preference, PDO::PARAM_INT);
        }
        $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setPetPreference($preference)
    {
        $sql = "UPDATE driver SET pets = :preference WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        if ($preference === null) {
            $stmt->bindValue(':preference', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':preference', $preference, PDO::PARAM_INT);
        }
        $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setFoodPreference($preference)
    {
        $sql = "UPDATE driver SET food = :preference WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        if ($preference === null) {
            $stmt->bindValue(':preference', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':preference', $preference, PDO::PARAM_INT);
        }
        $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setSpeakPreference($preference)
    {
        $sql = "UPDATE driver SET speaker = :preference WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        if ($preference === null) {
            $stmt->bindValue(':preference', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':preference', $preference, PDO::PARAM_INT);
        }
        $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function setMusicPreference($preference)
    {
        $sql = "UPDATE driver SET music = :preference WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        if ($preference === null) {
            $stmt->bindValue(':preference', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':preference', $preference, PDO::PARAM_INT);
        }
        $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }
}