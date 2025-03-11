<?php
require_once "../database.php";

class Driver extends User
{

    protected ?int $id;
    private bool|null $petPreference;
    private bool|null $smokerPreference;
    private bool|null $musicPreference;
    private bool|null $speakerPreference;
    private bool|null $foodPreference;

    public function __construct(PDO $pdo, int $driverId)
    {
        parent::__construct($pdo, $driverId); // Charge les données User
        $this->loadDriverFromDB();
        //$this->loadDriversRatings();
    }

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
        WHERE ratings.driver_id=:driver_id ORDER BY created_at DESC";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':driver_id', $this->id, PDO::PARAM_INT);
        $statement->execute();

        $ratingsData = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $ratingsData;
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

        $result = $this->petPreference;
        if (is_null($result)) {
            return null;
        }

        if ($result === true) {
            return "<div class='textIcon'>
                        <img src='../icons/AnimauxOk.png' class='imgFilter' alt=''> 
                        <span>J'aime la compagnie des animaux</span>
                    </div>";
        } elseif ($result === false) {
            return "<div class='textIcon'>
                        <img src='../icons/AnimauxPasOk.png' class='imgFilter' alt=''> 
                        <span>Je préfère ne pas voyager avec des animaux</span>
                    </div>";
        }
    }

    public function getSmokerPreference()
    {

        $result = $this->smokerPreference;
        if (is_null($result)) {
            return null;
        }

        if ($result === true) {
            return "<div class='textIcon'>
                        <img src='../icons/FumerOk.png' class='imgFilter' alt=''> 
                        <span>La fumée ne me dérange pas</span>
                    </div>";
        } elseif ($result === false) {
            return "<div class='textIcon'>
                        <img src='../icons/FumerPasOk.png' class='imgFilter' alt=''> 
                        <span>Je préfère ne pas voyager avec des fumeurs</span>
                    </div>";
        }
    }
    public function getMusicPreference()
    {

        $result = $this->musicPreference;
        if (is_null($result)) {
            return null;
        }

        if ($result === true) {
            return "<div class='textIcon'>
                        <img src='../icons/MusiqueOk.png' class='imgFilter' alt=''> 
                        <span>J'aime conduire en écoutant de la musique</span>
                    </div>";
        } elseif ($result === false) {
            return "<div class='textIcon'>
                        <img src='../icons/MusiquePasOk.png' class='imgFilter' alt=''> 
                        <span>Je préfère ne pas écouter de musique pendant que je conduis</span>
                    </div>";
        }
    }
    public function getSpeakerPreference()
    {

        $result = $this->speakerPreference;
        if (is_null($result)) {
            return null;
        }

        if ($result === true) {
            return "<div class='textIcon'>
                        <img src='../icons/speakOk.png' class='imgFilter' alt=''> 
                        <span>Je discute volontiers avec mes passagers</span>
                    </div>";
        } elseif ($result === false) {
            return "<div class='textIcon'>
                        <img src='../icons/speakNotOk.png' class='imgFilter' alt=''> 
                        <span>Je préfère me concentrer sur la route</span>
                    </div>";
        }
    }
    public function getFoodPreference()
    {

        $result = $this->foodPreference;
        if (is_null($result)) {
            return null;
        }

        if ($result === true) {
            return "<div class='textIcon'>
                        <img src='../icons/foodOk.png' class='imgFilter' alt=''> 
                        <span>La nourriture est autorisée dans la voiture </span>
                    </div>";
        } elseif ($result === false) {
            return "<div class='textIcon'>
                        <img src='../icons/foodNotOk.png' class='imgFilter' alt=''> 
                        <span>Pas de nourriture dans la voiture s'il vous plait</span>
                    </div>";
        }
    }

}