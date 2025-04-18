<?php

class User
{
    protected ?int $id;
    protected ?string $pseudo;
    protected ?string $mail;
    protected ?string $password;

    protected ?string $credit;

    protected ?string $photo;

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
        } elseif ($mail !== null && $password !== null) {
            $this->searchUserInDB($mail, $password);
        }
    }

    /**
     * Check that the e-mail address + password given are correct + check that the user is activated
     * @param mixed $mailTested
     * @param mixed $passwordTested
     * @throws \Exception
     * @return bool
     */
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
                if ($foundUserInDB['is_activated'] === 0) {
                    throw new Exception("Compte désactivé");
                } else {
                    // Récupération de l'ID depuis l'objet trouvé
                    if (isset($foundUserInDB['id'])) {
                        $this->id = $foundUserInDB['id'];
                        $this->idRole = $foundUserInDB['id_role'];
                        return true;
                    } else {
                        throw new Exception("Erreur : ID utilisateur non trouvé");
                    }
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
            $this->photo = $userData['photo'];
            $this->idRole = $userData['id_role'];
        } else {
            throw new Exception("Aucun utilisateur trouvé dans la BDD avec cet ID");
        }
    }

    /**
     * Create the user in DB. Can be a user or an employee
     * @param mixed $idRole // id = 1 if the user register on the login page (passenger) / id = 4 if the user is an employee created by the administrator
     * @return bool
     */
    public function saveUserToDatabase($idRole)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (pseudo, mail, password, id_role) VALUES (:pseudo, :mail, :password, :idRole)");
            $success = $stmt->execute([
                ':pseudo' => $this->pseudo,
                ':mail' => $this->mail,
                ':password' => $this->password,
                ':idRole' => $idRole,
            ]);

            if ($idRole === 1) {
                if ($success) {
                    $this->id = $this->pdo->lastInsertId(); // Retrieves new user ID
                    $this->setCredit(20);
                    $this->idRole = $idRole;
                }
            }
            return $success;

        } catch (PDOException $e) {
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }

    public function createDriver($pdo, $userId)
    {
        $sql = 'INSERT INTO driver (user_id) VALUES (:userId)';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * load a list of a type user
     * @param mixed $idRole //1 = passenger ; 2 = driver ; 3 = both ; 4 = employee ; 5 = administrator
     * @throws \Exception
     * @return mixed
     */
    public function loadListUsersFromDB($roleId)
    {
        $sql = "SELECT * FROM users WHERE id_role=:role_id ORDER BY pseudo ASC";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':role_id', $roleId, PDO::PARAM_INT);
        try {
            $statement->execute();
            $userData = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $userData;

        } catch (Exception $e) {
            new Exception("Aucun utilisateur trouvé dans la BDD avec ce role ID" . $e->getMessage());
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
    public function getPhoto()
    {
        return $this->photo;
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

    /**
     * Update the user's credit in DB
     * @param int $newCredit //default = 20 credits
     * @return void
     */
    private function setCredit(int $newCredit)
    {
        $sql = 'UPDATE users SET credit = :newCredit WHERE id = :idUser';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam('newCredit', $newCredit, PDO::PARAM_INT);
        $statement->bindParam('idUser', $this->id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function setIdRole($roleId)
    {
        $sql = "UPDATE users SET id_role = :roleId WHERE id = :userId";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        $statement->bindParam(':userId', $this->id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Active or desactive the user 
     * @param mixed $userId
     * @param mixed $isActivated // 1 = activated ; 0 = desactivated
     * @return void
     */
    public function setIsActivatedUser($userId, $isActivated)
    {
        $sql = "UPDATE users SET is_activated = :isActivated WHERE id = :userId";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':isActivated', $isActivated, PDO::PARAM_INT);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->execute();
    }

    public function setPhoto($photoUser){
        $sql = 'UPDATE users SET photo = :photo_user WHERE id = :user_id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':photo_user', $photoUser);
        $statement->bindParam(':user_id', $this->id, PDO::PARAM_INT);
        $statement->execute();
    }
}

?>