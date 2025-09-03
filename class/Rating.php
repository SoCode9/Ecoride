<?php

require_once __DIR__ . '/../functions.php';
class Rating
{
    private PDO $pdo;

    private ?string $userId;
    private ?string $driverId;

    private ?float $rating;
    private ?string $comment;
    private ?string $status;


    public function __construct(PDO $pdo, ?string $userId = null, ?string $driverId = null, ?float $rating = null, ?string $comment = null)
    {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->driverId = $driverId;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    /**
     * Saves a new rating from a user to a driver into the database
     * @param string $userId The ID of the user giving the rating
     * @param string $driverId The ID of the driver receiving the rating
     * @param float $newRating The rating value (e.g. 4.5)
     * @param string|null $newComment Optional comment left by the user
     * @throws \Exception If a database error occurs
     * @return bool True on success, false on failure
     */
    public function saveRatingToDatabase(string $userId, string $driverId, float $newRating, ?string $newComment = null): bool
    {
        try {
            $sql = 'INSERT INTO ratings (user_id, driver_id, rating, description) VALUES (:userId, :driverId, :rating, :comment)';
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':userId', $userId, PDO::PARAM_STR);
            $statement->bindParam(':driverId', $driverId, PDO::PARAM_STR);
            $statement->bindValue(':rating', $newRating);
            $statement->bindParam(':comment', $newComment, PDO::PARAM_STR);
            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in saveRatingToDatabase() : " . $e->getMessage());
            throw new Exception("Une erreur est survenue lors de l'enregistrement de la note");
        }
    }

    /**
     * Loads a paginated list of ratings that are pending validation, with associated user pseudonyms
     * @param int $limit The number of ratings to fetch (default: 5)
     * @param int $offset The offset for pagination (default: 0)
     * @return array An array of associative results (each including passenger and driver pseudonyms)
     * @throws Exception If a database error occurs
     */
    public function loadRatingsInValidation(int $limit = 5, int $offset = 0): array
    {
        try {
            $sql = "SELECT passenger.pseudo AS passenger_pseudo, driver.pseudo AS driver_pseudo, ratings.* FROM ratings 
                JOIN users AS passenger ON ratings.user_id = passenger.id
                JOIN users AS driver ON ratings.driver_id = driver.id
                WHERE status = 'in validation'
                ORDER BY created_at ASC
                LIMIT :limit OFFSET :offset";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in loadRatingsInValidation() : " . $e->getMessage());
            throw new Exception("Impossible de charger les évaluations en attente de validation");
        }
    }

    /**
     * Saves a new rating from a user to a driver into the database.
     * @throws \Exception If the database query fails
     * @return int Number of ratings with status 'in validation'
     */
    public function countAllRatingsInValidation(): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM ratings WHERE status = 'in validation'";
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return (int) $statement->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error in countAllRatingsInValidation(): " . $e->getMessage());
            throw new Exception("Impossible de compter les évaluations en attente de validation");
        }
    }

    /**
     * When an employee validate a rating, 
     * @param int $idRating rating id that is validated
     * @param string $newStatus Can be "refused" or "validated"
     * @return void
     */
    public function validateRating(int $idRating, string $newStatus): void
    {
        try {
            $sql = "UPDATE ratings SET status = :newStatus WHERE id = :idRating";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam('idRating', $idRating, PDO::PARAM_INT);
            $statement->bindParam('newStatus', $newStatus, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            error_log("Database error in validateRating(): " . $e->getMessage());
            throw new Exception("Erreur lors du changement de statut de l'avis");
        }
    }
}
