<?php
require_once "../database.php";


//Classe User pour création de compte
class User
{
    protected int $id;
    protected string $pseudo;
    protected string $mail;
    protected string $password;
    protected bool $chauffeur;

    protected string $telephone;
    protected string $adresse;
    protected string $dateNaissance;

    protected PDO $pdo; //stocke la connexion à la BDD
    /* public function __construct(string $pseudo, string $mail, string $password, bool $chauffeur = false)
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->pseudo = $pseudo;
        $this->mail = $mail;
        $this->password =password_hash($password, PASSWORD_BCRYPT) ; //sécuriser le password -> dans BDD le mdp apparait :$2y$10$cWmperOc2rMsBT1CxDPFXedJZ1f9VFqaTl0AcQudpo4.. 
        $this->chauffeur = $chauffeur;
    } */

    public function __construct(PDO $pdo, int $userId)
    {
        $this->pdo = $pdo;
        $this->id = $userId;
        $this->loadUserFromDB();
    }

    private function loadUserFromDB()
    {
        $sql = "SELECT * FROM users WHERE id=:user_id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':user_id', $this->id, PDO::PARAM_INT);
        $statement->execute();

        $userData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $this->id = $userData['id'];
            $this->pseudo = $userData['pseudo'];
            $this->mail = $userData['mail'];
            $this->password = $userData['password'];
            $this->chauffeur = $userData['chauffeur'];
            $this->telephone = $userData['telephone'];
            $this->adresse = $userData['adresse'];
            $this->dateNaissance = $userData['date_naissance'];
        } else {
            throw new Exception("Aucun utilisateur trouvé dans la BDD avec cet ID");
        }
    }

    public function saveUserToDatabase()
    {
        try {
            //Toujours utiliser les requêtes préparées avec prepare() et execute() pour éviter les injections SQL.
            $stmt = $this->pdo->prepare("INSERT INTO users (pseudo, mail, password, chauffeur) VALUES (:pseudo, :mail, :password, :chauffeur)"); //prepare($sql) → Prépare la requête sans l’exécuter immédiatement.
            return $stmt->execute([
                ':pseudo' => $this->pseudo,
                ':mail' => $this->mail,
                ':password' => $this->password,
                ':chauffeur' => $this->chauffeur
            ]);
        } catch (PDOException $e) {
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }

    /**
     * Sert pour afficher en tableau toutes les informations de l'utilisateur
     * @return array{Chauffeur ?: string, Mail: string, Password: string, Pseudo: string}
     */
    public function infoUserInArray(): array
    {
        return [
            "Pseudo" => $this->pseudo,
            "Mail" => $this->mail,
            "Password" => $this->password,
            "Chauffeur ?" => $this->chauffeur ? "Oui" : "Non"
        ];


    }


    //Getters et Setters

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function getMail()
    {
        return $this->mail;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function getChauffeur()
    {
        return $this->chauffeur;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    public function setPseudo(string $newPseudo)
    {
        $this->pseudo = $newPseudo;
    }
    public function setMail(string $newMail)
    {
        $this->mail = $newMail;
    }
    public function setPassword(string $newPassword)
    {
        $this->password = $newPassword;
    }
    public function setChauffeur(bool $newChauffeurType)
    {
        $this->chauffeur = $newChauffeurType;
    }
    public function setTelephone(string $newTelephone)
    {
        $this->telephone = $newTelephone;
    }
    public function setAdresse(string $newAdresse)
    {
        $this->adresse = $newAdresse;
    }
    public function setDateNaissance(string $newDateNaissance)
    {
        $this->dateNaissance = $newDateNaissance;
    }

}


//TESTS

/* $user1 = new User("Soso'w", "yoyo@aaa.com", "myPassword2024");

echo $user1->getPseudo() . PHP_EOL;

echo $user1->getChauffeur() . PHP_EOL;

$user1->setPseudo("mon Nouveau Pseudo");
echo $user1->getPseudo() . PHP_EOL;

//$user1->setChauffeur(true);
echo $user1->getChauffeur() . PHP_EOL;

$info = $user1->infoUserInArray();
var_dump($info); */

//CREATION COMPTE - FORMULAIRE

?>