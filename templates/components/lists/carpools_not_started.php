<div class="block-column-g20">


    <!--carpool finished but not validated-->
    <?php if (!empty($carpoolListToValidate)): ?>
        <h3 style="color: black ;">Covoiturages terminés, en attente de validation</h3>
    <?php endif; ?>
    <div class="flex-column gap-8 grid-auto-columns">
        <?php foreach ($carpoolListToValidate as $carpool): ?>
            <div class="travel" onclick="window.location.href='carpool_details.php?id=<?= htmlspecialchars($carpool['id']) ?>'"
                <?php if (($carpool['driver_id'] === $_SESSION['user_id'])) {
                    echo "style='border:2px solid var(--col-green);cursor:pointer;'";
                } else {
                    echo "style ='cursor:pointer;'";
                } ?>>
                <div class="user-header-mobile">
                    <div class="photo-user-container " style="justify-self:center;">
                        <img src="<?= displayPhoto($carpool['photo']) ?>" alt="Photo de l'utilisateur" class="photo-user">
                    </div>
                    <div class="user-info-mobile">
                        <span class="pseudo-user"><?= htmlspecialchars($carpool['pseudo']) ?></span>
                        <div class="driver-rating">
                            <?php
                            $driver = new Driver($pdo, $carpool['driver_id']);
                            $averageRating = $driver->getAverageRatings();
                            if ($averageRating !== null) {
                                echo '<img src="' . BASE_URL . '/icons/EtoileJaune.png" class="img-width-20" alt="Icone étoile">'
                                    . htmlspecialchars($averageRating);
                            } else {
                                echo "<span class = 'italic'>0 avis</span>";
                            } ?>
                        </div>
                    </div>
                </div>
                <span class="date-travel text-bold"><?= formatDate(htmlspecialchars($carpool['travel_date'])) ?></span>
                <span class="hours-travel">De <?= htmlspecialchars($carpool['travel_departure_city']) ?></span>
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
                    ?>
                </span>
                <?php if ($driver->getId() !== $idUser): ?>
                    <div class="btn action-btn" onclick="event.stopPropagation();" style="grid-column: 5/6; grid-row: 3/5;">
                        <button class="font-size-small" onclick="showPopupValidate(event)"
                            data-id="<?= $carpool['reservationId'] ?>" style="width: 100%;">Valider</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php include __DIR__ . '/../popup/carpool_to_validate.php'; ?>

    <!--carpool not started or in progress-->
    <?php if (!empty($carpoolListNotStarted)): ?>
        <h3 style="color: black ;">Covoiturages à venir</h3>
    <?php endif; ?>
    <div class="flex-column gap-8 grid-auto-columns">
        <?php foreach ($carpoolListNotStarted as $carpool): ?>
            <div class="travel" onclick="window.location.href='carpool_details.php?id=<?= htmlspecialchars($carpool['id']) ?>'"
                <?php if (($carpool['driver_id'] === $_SESSION['user_id'])) {
                    echo "style='border:2px solid var(--col-green); cursor:pointer;'";
                } else {
                    echo "style ='cursor:pointer;'";
                } ?>>
                <div class="user-header-mobile">
                    <div class="photo-user-container" style="justify-self:center;">
                        <img src="<?= displayPhoto($carpool['photo']) ?>" alt="Photo de l'utilisateur" class="photo-user">
                    </div>
                    <div class="user-info-mobile">
                        <span class="pseudo-user"><?= htmlspecialchars($carpool['pseudo']) ?></span>
                        <div class="driver-rating">
                            <?php
                            $driver = new Driver($pdo, $carpool['driver_id']);
                            $averageRating = $driver->getAverageRatings();
                            if ($averageRating !== null) {
                                echo '<img src="' . BASE_URL . '\icons\EtoileJaune.png" class="img-width-20" alt="Icone étoile">'
                                    . htmlspecialchars($averageRating);
                            } else {
                                echo "<span class = 'italic'>0 avis</span>";
                            } ?>
                        </div>
                    </div>
                </div>
                <span class="date-travel text-bold"><?= formatDate(htmlspecialchars($carpool['travel_date'])) ?> </span>
                <span class="hours-travel">De <?= htmlspecialchars($carpool['travel_departure_city']) ?></span>
                <span class="criteria-eco-div">À <?= htmlspecialchars($carpool['travel_arrival_city']) ?></span>
                <span class="seats-available">De
                    <?= formatTime(htmlspecialchars($carpool['travel_departure_time'])) ?> à
                    <?= formatTime(htmlspecialchars($carpool['travel_arrival_time'])) ?>
                </span>
                <span class="travel-price">
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
                    <div class="btn action-btn" onclick="event.stopPropagation();"
                        style="background-color:var(--col-light-grey); grid-column: 5/6; grid-row: 3/5;">
                        <a href="../back/user/user_space.php?action=cancel_carpool&id=<?= $carpool['id'] ?>"
                            class="font-size-small">Annuler</a>
                    </div>
                <?php endif; ?>

                <?php
                $now = new DateTime(); // current hour
                $departureDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $carpool['travel_date'] . ' ' . $carpool['travel_departure_time']);

                if (($carpool['travel_status'] === 'not started') && ($carpool['driver_id'] === $_SESSION['user_id']) && $departureDateTime !== false && $departureDateTime <= $now): ?>
                    <div class="btn action-btn" onclick="event.stopPropagation();"
                        style=" background-color:var(--col-light-green); grid-column: 5/6; grid-row: 3/5;">
                        <a href="../back/user/user_space.php?action=start_carpool&id=<?= $carpool['id'] ?>"
                            class="font-size-small">Démarrer</a>
                    </div>
                <?php endif; ?>
                <?php if (($carpool['travel_status'] === 'in progress') && ($carpool['driver_id'] === $_SESSION['user_id'])): ?>
                    <div class="btn action-btn" onclick="event.stopPropagation();"
                        style=" background-color:var(--col-light-green); grid-column: 5/6; grid-row: 3/5;">
                        <a href="../back/user/user_space.php?action=complete_carpool&id=<?= $carpool['id'] ?>"
                            class="font-size-small">Terminer</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>