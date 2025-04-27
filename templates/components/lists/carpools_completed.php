<div class="block-column-g20">

    <!--carpool finished and validated-->
    <?php if (!empty($carpoolListFinishedAndValidated)): ?>
        <h3 style="color: black ;">Covoiturages terminés</h3>
    <?php endif; ?>
    <div class="flex-column gap-8 grid-auto-columns">
        <?php foreach ($carpoolListFinishedAndValidated as $carpool): ?>
            <div class="travel" onclick="window.location.href='carpool_details.php?id=<?= htmlspecialchars($carpool['id']) ?>'"
                <?php if (($carpool['driver_id'] === $_SESSION['user_id'])) {
                    echo "style='border:2px solid var(--col-green);cursor:pointer;'";
                } else {
                    echo "style ='cursor:pointer;'";
                } ?>>
                <?php if ($carpool['travel_status'] === 'cancelled'): ?>
                    <span class="watermark-complet">Annulé</span>
                <?php endif; ?>
                <div class="user-header-mobile">
                    <div class="photo-user-container" style="justify-self:center;">
                        <img src="<?= displayPhoto($carpool['photo']) ?>" alt="Photo de l'utilisateur" class="photo-user">
                    </div>
                    <div class="user-info-mobile">
                        <span class="pseudo-user text-breakable"><?= htmlspecialchars($carpool['pseudo']) ?></span>
                        <div class="driver-rating">
                        <?php
                        $driver = new Driver($pdo, $carpool['driver_id']);                                            
                        $averageRating = $driver->getAverageRatings();
                        if ($averageRating !== null) {
                            echo '<img src="..\icons\EtoileJaune.png" class="img-width-20" alt="Icone étoile">'
                                    . htmlspecialchars($averageRating);
                        } else {
                            echo "<span class = 'italic'>0 avis</span>";
                        } ?>
                        </div>
                    </div>
                </div>
                <span class="date-travel text-bold"><?= formatDate(htmlspecialchars($carpool['travel_date'])) ?> </span>
                <span class="hours-travel">De <?= htmlspecialchars($carpool['travel_departure_city']) ?>
                </span>
                <span class="criteria-eco-div">À <?= htmlspecialchars($carpool['travel_arrival_city']) ?></span>
                <span class="seats-available">De
                    <?= formatTime(htmlspecialchars($carpool['travel_departure_time'])) ?> à
                    <?= formatTime(htmlspecialchars($carpool['travel_arrival_time'])) ?></span>
                <span class="travel-price">
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
</div>