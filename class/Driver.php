<?php
require_once "../database.php";

class Driver extends User
{

    protected int $id;
    private float $rating;

    private bool|null $petPreference;
    private bool|null $smokerPreference;
    private bool|null $musicPreference;
    private bool|null $speakerPreference;

    public function __construct(PDO $pdo, int $driverId)
    {
        parent::__construct($pdo, $driverId); // Charge les données User
        $this->loadDriverFromDB();
    }

    private function loadDriverFromDB()
    {

        $sql = "SELECT driver.*,users.* FROM driver JOIN users ON driver.user_id = users.id 
        WHERE driver.user_id = :driver_id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':driver_id', $this->id, PDO::PARAM_INT);
        $statement->execute();

        $driverData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($driverData) {
            // special Driver information
            $this->rating = (float) $driverData['driver_note'];
            $this->petPreference =  $driverData['pets'];
            $this->smokerPreference = $driverData['smoker'];
            $this->musicPreference = $driverData['music'];
            $this->speakerPreference = $driverData['speaker'];
            // Informations inherited from User
            $this->pseudo = $driverData['pseudo'];
            $this->mail = $driverData['mail'];
        } else {
            throw new Exception("Conducteur introuvable pour l'id {$this->id}.");
        }
    }

    public function getRating()
    {
        return $this->rating;
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

}