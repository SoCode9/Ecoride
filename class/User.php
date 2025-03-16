<?php
require_once "../database.php";


class User
{
    protected ?int $id;
    protected ?string $pseudo;
    protected ?string $mail;
    protected ?string $password;

    protected ?string $credit;

    protected ?int $idRole;
    protected ?PDO $pdo; //stocke la connexion à la BDD


    public function __construct(?PDO $pdo = null, ?int $userId = null, ?string $pseudo = null, ?string $mail = null, ?string $password = null)
    {
        $this->pdo = $pdo;
        $this->id = $userId;
        $this->pseudo = $pseudo;
        $this->mail = $mail;

        if ($userId !== null) {
            $this->loadUserFromDB();
        } elseif ($pseudo !== null && $mail !== null && $password !== null) {
            // New User created with informations given
            if (strlen($password) < 8) {
                throw new Exception("Le mot de passe est trop court (minimum 8 caractères)", 1);
            }
            if (
                !preg_match('/[A-Z]/', $password) ||  // At least one capital letter
                !preg_match('/[a-z]/', $password) ||  // At least one lower-case letter
                !preg_match('/[0-9]/', $password) ||  // At least one number
                !preg_match('/[\W]/', $password)  // At least one special character 
            ) {
                throw new Exception("Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial", 1);
            }
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        } /* elseif ($mail !== null && $password !== null) {
     $this->searchUserInDB($mail, $password);
 } */
    }

    public function searchUserInDB($mailTested, $passwordTested)
    {
        $sql = 'SELECT * FROM users WHERE (mail=:mailTested)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':mailTested', $mailTested, PDO::PARAM_STR);
        $stmt->execute();

        $foundUserInDB = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($foundUserInDB === false) {
            throw new Exception("Identifiants invalides");
        } else {
            if (password_verify($passwordTested, $foundUserInDB['password']) === false) {
                throw new Exception("Identifiants invalides");
            } else {
                //$this->id = $this->pdo->lastInsertId();
                //return true;
                // Récupération de l'ID depuis l'objet trouvé
                if (isset($foundUserInDB['id'])) {
                    $this->id = $foundUserInDB['id'];
                    return true;
                } else {
                    throw new Exception("Erreur : ID utilisateur non trouvé");
                }
            }
        }

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
            $this->credit = $userData['credit'];
            $this->idRole = $userData['id_role'];
        } else {
            throw new Exception("Aucun utilisateur trouvé dans la BDD avec cet ID");
        }
    }

    public function saveUserToDatabase()
    {
        try {
            //Toujours utiliser les requêtes préparées avec prepare() et execute() pour éviter les injections SQL.
            $stmt = $this->pdo->prepare("INSERT INTO users (pseudo, mail, password, credit, id_role) VALUES (:pseudo, :mail, :password, :credit, :idRole)"); //prepare($sql) → Prépare la requête sans l’exécuter immédiatement.
            $success = $stmt->execute([
                ':pseudo' => $this->pseudo,
                ':mail' => $this->mail,
                ':password' => $this->password,
                ':credit' => 20,
                'idRole' => 1,
            ]);

            if ($success) {
                $this->id = $this->pdo->lastInsertId(); // Retrieves new user ID
            }

            return $success;
        } catch (PDOException $e) {
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }

    //Getters et Setters

    public function getId()
    {
        return $this->id;
    }

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

    public function getCredit()
    {
        return $this->credit;
    }

    public function getIdRole()
    {
        return $this->idRole;
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

    public function setCredit(int $newCredit)
    {
        $this->credit = $newCredit;
    }

    public function setIdRole(int $newIdRole)
    {
        $this->idRole = $newIdRole;
    }

}

?>