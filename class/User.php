<?php

class User
{

    protected ?PDO $pdo;
    protected ?string $id;
    protected ?string $pseudo;
    protected ?string $mail;
    protected ?string $password;

    protected ?string $credit;

    protected ?string $photo;

    protected ?int $idRole = null;

    protected function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Register a new user and store it in the databaser
     * @param PDO $pdo
     * @param string $pseudo The user's username
     * @param string $mail The user's email address
     * @param string $password The plain text password to be hashed
     * @param int $roleId The role ID to assign to the user
     * @return User
     */
    public static function register(PDO $pdo, string $pseudo, string $mail, string $password, int $roleId): self
    {
        $user = new self($pdo);
        $user->pseudo = $pseudo;
        $user->mail = $mail;

        $user->validatePasswordStrength($password);
        $user->password = password_hash($password, PASSWORD_BCRYPT);

        $user->createNewUser($roleId);

        return $user;
    }

    /**
     * Authenticates a user with email and password, and loads their data
     * @param PDO $pdo
     * @param string $mail The user's email address
     * @param string $password The plain text password
     * @return User
     */
    public static function login(PDO $pdo, string $mail, string $password): self
    {
        $user = new self($pdo);
        $user->searchUserInDB($mail, $password);
        return $user;
    }

    /**
     * Loads a user from the database using their ID
     * @param PDO $pdo
     * @param string $userId The ID of the user to load
     * @return User
     */
    public static function fromId(PDO $pdo, string $userId): self
    {
        $user = new self($pdo);
        $user->id = $userId;
        $user->loadUserFromDB();
        return $user;
    }

    /**
     * Validates the strength of the user's password.
     * A valid password must be at least 8 characters long,
     * and contain at least one uppercase letter, one lowercase letter,
     * one digit, and one special character.
     * @param string $password The plain text password to validate
     * @throws \Exception If the password is too weak
     * @return void 
     */
    private function validatePasswordStrength(string $password): void
    {
        if (strlen($password) < 8) {
            throw new Exception("Le mot de passe est trop court (minimum 8 caractères)");
        }

        if (
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W]/', $password)
        ) {
            throw new Exception("Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial");
        }
    }

    /**
     * Authenticate a user by verifying the email and password.
     * Initializes the object if credentials are valid and the account is active.
     * @param string $mailTested The email address provided by the user
     * @param string $passwordTested The plain password provided by the user
     * @throws \Exception If authentication fails (invalid credentials or inactive account)
     * @return void
     */
    public function searchUserInDB(string $mailTested, string $passwordTested): void
    {
        $sql = 'SELECT * FROM users WHERE (mail=:mailTested)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':mailTested', $mailTested, PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (
            ($user === false)
            || ($user['is_activated'] === 0)
            || (password_verify($passwordTested, $user['password']) === false)
            || (!isset($user['id']))
        ) {
            throw new Exception("Identifiants invalides");
        }

        $this->id = $user['id'];
        $this->idRole = $user['id_role'];
    }
    /**
     * Loads user data from the database using the current user ID
     * @throws \Exception If no user is found with the given ID
     * @return void
     */
    protected function loadUserFromDB(): void
    {
        $sql = "SELECT * FROM users WHERE id=:user_id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':user_id', $this->id, PDO::PARAM_STR);
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
            error_log("loadUserFromDB() failed for user ID: {$this->id}");
            throw new Exception("Une erreur est survenue lors du chargement des informations de l'utilisateur");
        }
    }

    /**
     * Creates a new user in the database. Can be a regular user or an employee
     * @param int $idRole Role ID: 1 for self-registered users (passenger), 4 for admin-created employees
     * @throws \Exception If the user already exists or a database error occurs.
     * @return void
     */
    public function createNewUser(int $idRole): void
    {
        if ($this->getIdInDB() !== null) {
            error_log("Account already exists for this email address: {$this->getIdInDB()}");
            throw new Exception("Une erreur est survenue");
        }

        try {
            $statement = $this->pdo->prepare("INSERT INTO users (id, pseudo, mail, password, id_role) VALUES (UUID(), :pseudo, :mail, :password, :idRole)");
            $success = $statement->execute([
                ':pseudo' => $this->pseudo,
                ':mail' => $this->mail,
                ':password' => $this->password,
                ':idRole' => $idRole,
            ]);

            if ($success) {
                $this->getIdInDB();
                $this->idRole = $idRole;

                if ($idRole === 1) {
                    $this->setCredit(20);
                }
            }
        } catch (PDOException $e) {
            error_log("Database error in createNewUser(): " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    /**
     * Creates a new driver profile linked to a user
     * @param string $userId The UUID of the user
     * @throws \Exception If the insertion fails
     * @return void
     */
    public function createDriver(string $userId): void
    {
        try {
            $sql = 'INSERT INTO driver (user_id) VALUES (:userId)';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':userId', $userId, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in createDriver(): " . $e->getMessage());
            throw new Exception("Impossible de créer un profil conducteur");
        }
    }

    public function isDriver(string $userId): bool
    {
        try {
            $sql = "SELECT user_id FROM driver WHERE user_id=:userId";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error in isDriver(): ($userId): " . $e->getMessage());
            throw new Exception("Impossible de savoir si l'utilisateur est un chauffeur");
        }
    }

    /**
     * Loads a list of users by role
     * @param int $idRole Role ID to filter users (1 = passenger ; 2 = driver ; 3 = both ; 4 = employee ; 5 = administrator)
     * @throws \Exception If the query fails
     * @return array List of users as associative arrays
     */
    public function loadListUsersFromDB(int $roleId): array
    {
        try {
            $sql = "SELECT * FROM users WHERE id_role=:role_id ORDER BY pseudo ASC";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':role_id', $roleId, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error loading users by role ($roleId): " . $e->getMessage());
            throw new Exception("Impossible de charger la liste des utilisateurs");
        }
    }

    //Getters et Setters

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Retrieves the user ID from the database using the stored email address.
     * @throws \Exception If the email is not set or if the query fails.
     * @return string|null The user's ID if found, or null if not found.
     */
    public function getIdInDB(): ?string
    {
        if (empty($this->mail)) {
            throw new Exception("Aucune adresse email définie pour récupérer l'ID utilisateur");
        }

        try {
            $sql = "SELECT id FROM users WHERE mail = :mail";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':mail', $this->mail, PDO::PARAM_STR);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->id = $result['id'] ?? null;

            return $this->id;
        } catch (PDOException $e) {
            error_log("Database error in getIdInDB() (mail: {$this->mail}) : " . $e->getMessage());
            throw new Exception("Impossible de récupérer l'identifiant de l'utilisateur");
        }
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function getIdRole(): ?int
    {
        return $this->idRole;
    }

    /**
     * Update the user's credit in DB
     * @param int $newCredit //default = 20 credits
     * @return void
     */
    public function setCredit(int $newCredit): void
    {
        if (empty($this->id)) {
            throw new Exception("Impossible de mettre à jour les crédits sans identifiant utilisateur");
        }

        try {
            $sql = 'UPDATE users SET credit = :newCredit WHERE id = :idUser';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam('newCredit', $newCredit, PDO::PARAM_INT);
            $statement->bindParam('idUser', $this->id, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in setCredit() (idUser: {$this->id}) : " . $e->getMessage());
            throw new Exception("Impossible de mettre à jour les crédits");
        }
    }

    /**
     * Updates the user's role in the database
     * @param int $roleId The new role ID to assign to the user
     * @throws \Exception If the user ID is not set or the update fails
     * @return void
     */
    public function setIdRole(int $roleId): void
    {
        if (empty($this->id)) {
            throw new Exception("Impossible de définir un rôle sans identifiant utilisateur");
        }

        try {
            $sql = "UPDATE users SET id_role = :roleId WHERE id = :userId";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':roleId', $roleId, PDO::PARAM_INT);
            $statement->bindParam(':userId', $this->id, PDO::PARAM_STR);
            $statement->execute();

            $this->idRole = $roleId;
        } catch (PDOException $e) {
            error_log("Database error in setIdRole() (user ID: {$this->id}) : " . $e->getMessage());
            throw new Exception("Impossible de mettre à jour le rôle de l'utilisateur");
        }
    }

    /**
     * Activates or deactivates a user account in the database
     * @param string $userId The ID of the user to update
     * @param bool $isActivated True to activate, false to deactivate
     * @throws \Exception If the update fails or the user ID is invalid
     * @return void
     */
    public function setIsActivatedUser(string $userId, bool $isActivated): void
    {
        if (empty($userId)) {
            throw new Exception("Aucun ID utilisateur fourni pour l'activation");
        }

        try {
            $sql = "UPDATE users SET is_activated = :isActivated WHERE id = :userId";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':isActivated', $isActivated ? 1 : 0, PDO::PARAM_INT);
            $statement->bindParam(':userId', $userId, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in setIsActivatedUser() (user ID: $userId) : " . $e->getMessage());
            throw new Exception("Impossible de modifier le statut du compte utilisateur");
        }
    }

    /**
     * Updates the user's profile photo in the database
     * @param string $photoUser The path or name of the user's photo
     * @throws \Exception If the user ID is not set or the update fails
     * @return void
     */
    public function setPhoto(string $photoUser): void
    {
        if (empty($this->id)) {
            throw new Exception("Impossible de modifier la photo sans identifiant utilisateur");
        }

        try {
            $sql = 'UPDATE users SET photo = :photo_user WHERE id = :user_id';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':photo_user', $photoUser, PDO::PARAM_STR);
            $statement->bindParam(':user_id', $this->id, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in setPhoto() (user ID: {$this->id}) : " . $e->getMessage());
            throw new Exception("Impossible de mettre à jour la photo de profil");
        }
    }
}
