<?php

class Rating
{
    private PDO $pdo;

    private int $userId;
    private int $driverId;

    private float $rating;
    private string $comment;
    private string $status;


    public function __construct(PDO $pdo, int $userId, int $driverId, float $rating, ?string $comment = null)
    {
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
}