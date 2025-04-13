<div class="filtersList">

    <!--carpool finished and validated-->
    <?php if (!empty($carpoolListFinishedAndValidated)): ?>
        <h2 class="subTitleGreen" style="color: black ;">Covoiturages terminés</h2>
    <?php endif; ?>
    <?php foreach ($carpoolListFinishedAndValidated as $carpool): ?>
        <div class="travel" <?php if (($carpool['driver_id'] === $_SESSION['user_id'])) {
            echo "style='border:2px solid #4D9856;'";
        } ?>>
            <?php if ($carpool['travel_status'] === 'cancelled'): ?>
                <span class="watermark-complet">Annulé</span>
            <?php endif; ?>

            <img src="../icons/Femme3.jpg" alt="Photo de l'utilisateur" class="photoUser">
            <span class="pseudoUser"><?= htmlspecialchars($carpool['pseudo']) ?></span>
            <div class="driverRating">
                <img src="../icons/EtoileJaune.png" alt="Etoile" class="imgFilter">
                <span>
                    <?php
                    $driver = new driver($pdo, $carpool['driver_id']);
                    $rating = $driver->getAverageRatings();
                    echo $rating;
                    ?>
                </span>
            </div>
            <span class="dateTravel"><?= formatDate(htmlspecialchars($carpool['travel_date'])) ?> </span>
            <span class="hoursTravel">De <?= htmlspecialchars($carpool['travel_departure_city']) ?>
            </span>
            <span class="seatsAvailable">De
                <?= formatTime(htmlspecialchars($carpool['travel_departure_time'])) ?> à
                <?= formatTime(htmlspecialchars($carpool['travel_arrival_time'])) ?></span>
            <span class="criteriaEcoDiv">À <?= htmlspecialchars($carpool['travel_arrival_city']) ?></span>
            <span class="travelPrice gras">
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