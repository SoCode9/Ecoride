<div class="block-column-g20">


    <!--carpool finished but not validated-->

    <?php if (!empty($carpoolListToValidate)): ?>
        <h2 class="subTitleGreen" style="color: black ;">Covoiturages terminés, en attente de validation</h2>
    <?php endif; ?>
    <?php foreach ($carpoolListToValidate as $carpool): ?>
        <div class="travel" <?php if (($carpool['driver_id'] === $_SESSION['user_id'])) {
            echo "style='border:2px solid var(--col-green);'";
        } ?>>

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
            <span class="date-travel" bold><?= formatDate(htmlspecialchars($carpool['travel_date'])) ?> </span>
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
            <?php if ($driver->getId() !== $idUser): ?>
                <div class="btn action-btn"style="grid-column: 5/6; grid-row: 3/5;">
                    <button class="font-size-small" onclick="showPopup(event)" data-id="<?= $carpool['reservationId'] ?>"
                        style="width: 100%;">Valider</button>
                </div>

            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <?php include '../templates/components/carpool_to_validate_popup.php'; ?>
    <!--carpool not started or in progress-->
    <?php if (!empty($carpoolListNotStarted)): ?>
        <h3 style="color: black ;">Covoiturages à venir</h3>
    <?php endif; ?>
    <?php foreach ($carpoolListNotStarted as $carpool): ?>
        <div class="travel" <?php if (($carpool['driver_id'] === $_SESSION['user_id'])) {
            echo "style='border:2px solid var(--col-green)'";
        } ?>>

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
            <span class="date-travel bold"><?= formatDate(htmlspecialchars($carpool['travel_date'])) ?> </span>
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
                ?>
            </span>
            <?php if ($carpool['travel_status'] === 'not started'): ?>
                <div class="btn action-btn" style="background-color:#EDEDED; grid-column: 5/6; grid-row: 3/5;">
                    <a href="../back/user/user_space.php?action=cancel_carpool&id=<?= $carpool['id'] ?>"
                        class="font-size-small">Annuler</a>
                </div>
            <?php endif; ?>

            <?php
            $now = new DateTime(); // current hour
            $departureDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $carpool['travel_date'] . ' ' . $carpool['travel_departure_time']);

            if (($carpool['travel_status'] === 'not started') && ($carpool['driver_id'] === $_SESSION['user_id']) && $departureDateTime !== false && $departureDateTime <= $now): ?>
                <div class="btn action-btn" style=" background-color:var(--col-light-green); grid-column: 5/6; grid-row: 3/5;">
                    <a href="../back/user/user_space.php?action=start_carpool&id=<?= $carpool['id'] ?>"
                        class="font-size-small">Démarrer</a>
                </div>
            <?php endif; ?>
            <?php if (($carpool['travel_status'] === 'in progress') && ($carpool['driver_id'] === $_SESSION['user_id'])): ?>
                <div class="btn action-btn" style=" background-color:var(--col-light-green); grid-column: 5/6; grid-row: 3/5;">
                    <a href="../back/user/user_space.php?action=complete_carpool&id=<?= $carpool['id'] ?>"
                        class="font-size-small">Terminer</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>