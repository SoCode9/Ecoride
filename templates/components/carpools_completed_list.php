<div class="block-column-g20">

    <!--carpool finished and validated-->
    <?php if (!empty($carpoolListFinishedAndValidated)): ?>
        <h3 style="color: black ;">Covoiturages terminés</h3>
    <?php endif; ?>
    <?php foreach ($carpoolListFinishedAndValidated as $carpool): ?>
        <div class="travel" <?php if (($carpool['driver_id'] === $_SESSION['user_id'])) {
            echo "style='border:2px solid var(--col-green);'";
        } ?>>
            <?php if ($carpool['travel_status'] === 'cancelled'): ?>
                <span class="watermark-complet">Annulé</span>
            <?php endif; ?>

            <img src="../icons/Femme3.jpg" alt="Photo de l'utilisateur" class="photo-user">
            <span class="pseudo-user"><?= htmlspecialchars($carpool['pseudo']) ?></span>
            <div class="driver-rating">
                <img src="../icons/EtoileJaune.png" alt="Etoile" class="img-width-20">
                <span>
                    <?php
                    $driver = new driver($pdo, $carpool['driver_id']);
                    $rating = $driver->getAverageRatings();
                    echo $rating;
                    ?>
                </span>
            </div>
            <span class="date-travel"><?= formatDate(htmlspecialchars($carpool['travel_date'])) ?> </span>
            <span class="hours-travel">De <?= htmlspecialchars($carpool['travel_departure_city']) ?>
            </span>
            <span class="seats-available">De
                <?= formatTime(htmlspecialchars($carpool['travel_departure_time'])) ?> à
                <?= formatTime(htmlspecialchars($carpool['travel_arrival_time'])) ?></span>
            <span class="criteria-eco-div">À <?= htmlspecialchars($carpool['travel_arrival_city']) ?></span>
            <span class="travel-price gras">
                <?php
                $trajetPrice = htmlspecialchars($carpool['travel_price']);
                if ($trajetPrice > 1) {
                    echo $trajetPrice . " crédits";
                } else {
                    echo $trajetPrice . " crédit";
                }
                ?></span>

        </div>
    <?php endforeach; ?>

</div>