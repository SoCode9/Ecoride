<main>

    <!--Travel's details and booking block-->

    <h2 class="text-green text-bold">
        <?= formatDateLong(htmlspecialchars($travel->getDate($travel->getIdTravel()))) ?? '' ?>
    </h2>

    <section class="flex-row flex-between block-light-grey flex-column-ss no-background-ss">
        <div class="flex-row flex-between block-white" id="travel-details" style="width:65%;box-sizing: border-box;">

            <div class="course">

                <div class="time-location-ellipse">
                    <div class="flex-column gap-8 time-location">
                        <span><?= formatTime(htmlspecialchars($travel->getDepartureTime())) ?></span>
                        <span><?= htmlspecialchars($travel->getDepartureCity()) ?></span>
                    </div>

                    <div id="dot"></div>
                </div>

                <div class="line-container">
                    <div class="line"></div>
                    <div class="duration text-green">
                        <?= htmlspecialchars($travel->travelDuration($travel->getDepartureTime(), $travel->getArrivalTime())) ?>
                    </div>
                    <div class="line"></div>
                </div>

                <div class="time-location-ellipse">
                    <div id="dot"></div>
                    <div class="flex-column gap-8 time-location">
                        <span><?= formatTime(htmlspecialchars($travel->getArrivalTime())) ?></span>
                        <span><?= htmlspecialchars($travel->getArrivalCity()) ?></span>
                    </div>

                </div>

            </div>

            <div class="flex-column gap-8" id="travel-extra" style="align-items: end; min-width:max-content;">
                <div> <?php $seatsAvailable = seatsAvailable($car->getSeatsOfferedByCar($travel->getCarId()), ($reservation->countPassengers($travel->getIdTravel())));

                if ($seatsAvailable <= 1) {
                    echo htmlspecialchars($seatsAvailable) . " place restante";
                } else {
                    echo htmlspecialchars($seatsAvailable) . " places restantes";
                }

                ?>
                </div>
                <div>
                    <span class="criteria-eco"><?= formatEco(($car->getElectric())) ?></span>
                </div>
            </div>

        </div>
        <div class="flex-column gap-12" id="passenger-credits-btn" style="width: 30%;">
            <div class="flex-row flex-between block-white">
                <div>1 passager</div>
                <div class="text-bold"><?php $travelPrice = $travel->getPrice();
                if ($travelPrice > 1) {
                    echo htmlspecialchars($travelPrice) . " crédits";
                } else {
                    echo htmlspecialchars($travelPrice) . " crédit";
                }
                ?> </div>
            </div>
            <?php $isGuest = $userId === null;
            $isNotEmployee = !$isGuest && in_array($user->getIdRole(), [1, 2, 3]);
            $isNotDriver = $travel->getDriverId() != $userId;
            $status = $travel->getStatus();
            if (($isGuest || $isNotEmployee) && $isNotDriver && $status === 'not started'): ?>
                <button id="participate" class="btn action-btn" style="padding: 8px;"
                    data-id="<?= htmlspecialchars($travel->getIdTravel()) ?>">
                    <img src="<?= BASE_URL ?>/icons/Calendrier.png" class="img-pointer" alt="booking calendar icon">
                    <span>Participer au covoiturage</span>
                </button>
            <?php endif; ?>

            <?php if ($travel->getDriverId() === $userId && $status === 'not started'): ?>
                <div class="btn action-btn" style="padding: 8px;">
                    <a href="../back/user/user_space.php?action=cancel_carpool&id=<?= $travel->getIdTravel() ?>">Annuler le
                        covoiturage</a>
                </div>
            <?php endif; ?>
        </div>

    </section>

    <div style="display: flex; justify-content: space-between;" class="gap-24 flex-column-ms flex-column-ss">

        <!--Driver's details-->

        <section class="block-driver-info block-light-grey flex-column-ss">
            <div class="flex-column gap-24 flex-row-ss">
                <img src="<?= displayPhoto($driver->getPhoto()) ?>" class="photo-100" alt="photo de l'utilisateur">
                <div class="flex-column gap-12 flex-center item-center">
                    <span><?= htmlspecialchars($driver->getPseudo()) ?></span>
                    <div class="text-icon" style="padding-left: 0px;">
                        <img src="<?= BASE_URL ?>/icons/EtoileJaune.png" class="img-width-20" alt="">
                        <span><?php $averageRating = $driver->getAverageRatings();
                        if ($averageRating !== null) {
                            echo htmlspecialchars($averageRating);
                        } else {
                            echo "(0 avis)";
                        } ?> </span>
                    </div>
                </div>
            </div>

            <div class="flex-column gap-24">


                <?php $travelDescription = $travel->getDescription();
                ;

                if ($travelDescription != null) {
                    ?>
                    <p>
                        <?= htmlspecialchars($travelDescription); ?>
                    </p>
                <?php } ?>


                <div class="flex-column gap-12">
                    <div class="text-bold">Véhicule</div>
                    <div class="text-icon">
                        <img src="<?= BASE_URL ?>/icons/Voiture.png" class="img-width-20" alt="">
                        <span><?php
                        echo htmlspecialchars($car->getBrand() . " " . $car->getModel() . " - " . $car->getColor());
                        if ($car->getElectric() === true) {
                            echo " - Electrique";
                        }
                        ?></span>
                    </div>
                </div>

                <div class="flex-column gap-12">
                    <div class="text-bold">Préférences</div>
                    <?php
                    $petPref = $driver->getPetPreference();
                    if ($petPref === true): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/AnimauxOk.png' class='img-width-20' alt=''>
                            <span>J'aime la compagnie des animaux</span>
                        </div>
                    <?php elseif ($petPref === false): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/AnimauxPasOk.png' class='img-width-20' alt=''>
                            <span>Je préfère ne pas voyager avec des animaux</span>
                        </div>
                    <?php endif; ?>


                    <?php
                    $smokerPref = $driver->getSmokerPreference();
                    if ($smokerPref === true): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/FumerOk.png' class='img-width-20' alt=''>
                            <span>La fumée ne me dérange pas</span>
                        </div>
                    <?PHP elseif ($smokerPref === false): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/FumerPasOk.png' class='img-width-20' alt=''>
                            <span>Je préfère ne pas voyager avec des fumeurs</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $musicPref = $driver->getMusicPreference();
                    if ($musicPref === true): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/MusiqueOk.png' class='img-width-20' alt=''>
                            <span>J'aime conduire en écoutant de la musique</span>
                        </div>
                    <?PHP elseif ($musicPref === false): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/MusiquePasOk.png' class='img-width-20' alt=''>
                            <span>Je préfère ne pas écouter de musique pendant que je conduis</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $speakerPref = $driver->getSpeakerPreference();
                    if ($speakerPref === true): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/speakOk.png' class='img-width-20' alt=''>
                            <span>Je discute volontiers avec mes passagers</span>
                        </div>
                    <?PHP elseif ($speakerPref === false): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/speakNotOk.png' class='img-width-20' alt=''>
                            <span>Je préfère me concentrer sur la route</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $foodPref = $driver->getFoodPreference();
                    if ($foodPref === true): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/foodOk.png' class='img-width-20' alt=''>
                            <span>La nourriture est autorisée dans la voiture </span>
                        </div>
                    <?PHP elseif ($foodPref === false): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/foodNotOk.png' class='img-width-20' alt=''>
                            <span>Pas de nourriture dans la voiture s'il vous plait</span>
                        </div>
                    <?php endif; ?>
                    <!--Others preferences-->
                    <?php
                    $addPref1 = $driver->getAddPref1();
                    if (isset($addPref1)): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/addPref.png' class='img-width-20' alt=''>
                            <span><?= htmlspecialchars($addPref1) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $addPref2 = $driver->getAddPref2();
                    if (isset($addPref2)): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/addPref.png' class='img-width-20' alt=''>
                            <span><?= htmlspecialchars($addPref2) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $addPref3 = $driver->getAddPref3();
                    if (isset($addPref3)): ?>
                        <div class="text-icon">
                            <img src='<?= BASE_URL ?>/icons/addPref.png' class='img-width-20' alt=''>
                            <span><?= htmlspecialchars($addPref3) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!--RATING'S DRIVER BLOCK-->


        <section class="flex-column block-light-grey gap-12 block-driver-ratings w-100-ss">

            <div class="flex-column gap-8 item-center">
                <h3 class="text-green">Avis du chauffeur</h3>
                <div class="flex-row item-center gap-4" style="padding-left: 0px;">
                    <img src="<?= BASE_URL ?>/icons/EtoileJaune.png" class="img-width-20" alt="">
                    <span><?php $averageRating = $driver->getAverageRatings();
                    if ($averageRating !== null) {
                        echo htmlspecialchars($averageRating) . " / 5";
                    } ?>

                    </span>
                    <span
                        class="font-size-very-small"><?= "(" . htmlspecialchars($driver->countRatings()) . " avis)" ?></span>
                </div>
            </div>


            <!--ratings list-->
            <?php $ratingsList = $driver->loadValidatedRatings();
            foreach ($ratingsList as $rating): ?>

                <div class="flex-column gap-8">
                    <div class="flex-row flex-between">
                        <div class="flex-row item-center gap-4">
                            <img src="<?= displayPhoto($rating['photo']) ?>" alt="Photo de l'utilisateur" class="photo-50">
                            <span><?= htmlspecialchars($rating['pseudo']) ?></span>
                        </div>
                        <div class="flex-row item-center gap-4" style="padding-left: 0px;">
                            <img src="<?= BASE_URL ?>/icons/EtoileJaune.png" class="img-width-20" alt="">
                            <span><?= htmlspecialchars($rating['rating']) ?></span>

                        </div>
                    </div>
                    <p><?= htmlspecialchars(($rating['description'])) ?></p>
                    <span
                        class="font-size-very-small italic"><?= htmlspecialchars(formatDateMonthAndYear($rating['created_at'])) ?></span>
                    <hr>
                </div>
            <?php endforeach ?>



        </section>

    </div>

</main>