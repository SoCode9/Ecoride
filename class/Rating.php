<?php

require_once __DIR__ . '/../functions.php';
class Rating
{
    private PDO $pdo;

    private int|null $userId;
    private int|null $driverId;

    private float|null $rating;
    private string|null $comment;
    private string $status;


    public function __construct(PDO $pdo, ?int $userId = null, ?int $driverId = null, ?float $rating = null, ?string $comment = null)
    {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->driverId = $driverId;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function saveRatingToDatabase(PDO $pdo, int $userId, int $driverId, float $newRating, ?string $newComment = null)
    {
        $sql = 'INSERT INTO ratings (user_id, driver_id, rating, description) VALUES (:userId, :driverId, :rating, :comment)';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':driverId', $driverId, PDO::PARAM_INT);
        $statement->bindParam(':rating', $newRating, PDO::PARAM_INT);
        $statement->bindParam(':comment', $newComment, PDO::PARAM_STR);
        try {
            return $statement->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la sauvegarde de l'avis dans la table ratings : " . $e->getMessage());
        }
    }

    /**
     * Loading of all ratings awaiting validation, validation by employees
     * @return array return pseudo passenger/driver, and all info about the ratings
     */
    public function loadRatingsInValidation($limit = 5, $offset = 0)
    {
        $sql = "SELECT passenger.pseudo AS passenger_pseudo, driver.pseudo AS driver_pseudo, ratings.* FROM ratings 
        JOIN users AS passenger ON ratings.user_id = passenger.id
        JOIN users AS driver ON ratings.driver_id = driver.id
        WHERE status = 'in validation'
        ORDER BY created_at ASC
        LIMIT :limit OFFSET :offset";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $ratingsInValidation = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $ratingsInValidation;
    }

    public function countAllRatingsInValidation()
    {
        $sql = "SELECT COUNT(*) FROM ratings WHERE status = 'in validation'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return (int) $statement->fetchColumn();
    }

    public function validateRating($pdo, $idRating, $newStatus)
    {
        $sql = "UPDATE ratings SET status = :newStatus WHERE id = :idRating";
        $statement = $pdo->prepare($sql);
        $statement->bindParam('idRating', $idRating, PDO::PARAM_INT);
        $statement->bindParam('newStatus', $newStatus, PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch (Exception $e) {
            new Exception("Erreur lors du changement de statut de l'avis : " . $e);
        }
    }
}