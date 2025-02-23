<?php
require_once "../database.php";


//Classe User pour création de compte
class User
{

    private string $pseudo;
    private string $mail;
    private string $password;
    private bool $chauffeur;
    private PDO $pdo; //stocke la connexion à la BDD
    public function __construct(string $pseudo, string $mail, string $password, bool $chauffeur = false)
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->pseudo = $pseudo;
        $this->mail = $mail;
        $this->password =password_hash($password, PASSWORD_BCRYPT) ; //sécuriser le password -> dans BDD le mdp apparait :$2y$10$cWmperOc2rMsBT1CxDPFXedJZ1f9VFqaTl0AcQudpo4.. 
        $this->chauffeur = $chauffeur;
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