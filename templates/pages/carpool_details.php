<main>

    <!--Travel's details and booking block-->

    <h2 class="text-green text-bold">
        <?= formatDateLong(htmlspecialchars($_SESSION['departure-date-search'])) ?? '' ?>
    </h2>

    <section class="flex-row flex-between block-light-grey">
        <div class="flex-row flex-between block-white" style="width:65%;box-sizing: border-box;">

            <div class="course">

                <div class="time-location-ellipse">
                    <div class="flex-column gap-8 time-location">
                        <span><?= formatTime(htmlspecialchars($travel->getDepartureTime())) ?></span>
                        <span><?= htmlspecialchars($travel->getDepartureCity()) ?></span>
                    </div>

                    <div id="ellipse"></div>
                </div>

                <div class="duration-line">
                    <div class="text-green">
                        <?= htmlspecialchars($travel->travelDuration($travel->getDepartureTime(), $travel->getArrivalTime())) ?>
                    </div>
                    <div class="line"></div>
                </div>

                <div class="time-location-ellipse">
                    <div id="ellipse"></div>
                    <div class="flex-column gap-8 time-location">
                        <span><?= formatTime(htmlspecialchars($travel->getArrivalTime())) ?></span>
                        <span><?= htmlspecialchars($travel->getArrivalCity()) ?></span>
                    </div>

                </div>

            </div>

            <div class="flex-column gap-8" style="align-items: end;">
                <div> <?php $seatsAvailable = seatsAvailable($car->nbSeatsOfferedInACarpool($pdo, $travel->getCarId()), ($reservation->nbPassengerInACarpool($pdo, $travel->getIdTravel())));

                if ($seatsAvailable <= 1) {
                    echo htmlspecialchars($seatsAvailable) . " place restante";
                } else {
                    echo htmlspecialchars($seatsAvailable) . " places restantes";
                }

                ?>
                </div>
                <div>
                    <span class="criteria-eco"><?php
                    echo formatEco(($car->getElectric())) ?></span>
                </div>
            </div>

        </div>
        <div class="flex-column gap-12" style="width: 30%;">
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

            <button id="participate" class="btn action-btn" style="padding: 8px;"
                data-id="<?= htmlspecialchars($travel->getIdTravel()) ?>">
                <img src="../icons/Calendrier.png" class="img-pointer" alt="booking calendar icon">
                <span>Participer au covoiturage</span>
            </button>
        </div>

    </section>

    <div style="display: flex; justify-content: space-between;" class="gap-24">

        <!--Driver's details-->

        <section class="block-driver-info block-light-grey">
            <div class="flex-column gap-24">
                <img src="<?= displayPhoto($driver->getPhoto()) ?>" class="photo" alt="photo de l'utilisateur">
                <div class="flex-column gap-12 flex-center item-center">
                    <span><?= htmlspecialchars($driver->getPseudo()) ?></span>
                    <div class="text-icon" style="padding-left: 0px;">
                        <img src="../icons/EtoileJaune.png" class="img-width-20" alt="">
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
                        <img src="../icons/Voiture.png" class="img-width-20" alt="">
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
                            <img src='../icons/AnimauxOk.png' class='img-width-20' alt=''>
                            <span>J'aime la compagnie des animaux</span>
                        </div>
                    <?php elseif ($petPref === false): ?>
                        <div class="text-icon">
                            <img src='../icons/AnimauxPasOk.png' class='img-width-20' alt=''>
                            <span>Je préfère ne pas voyager avec des animaux</span>
                        </div>
                    <?php endif; ?>


                    <?php
                    $smokerPref = $driver->getSmokerPreference();
                    if ($smokerPref === true): ?>
                        <div class="text-icon">
                            <img src='../icons/FumerOk.png' class='img-width-20' alt=''>
                            <span>La fumée ne me dérange pas</span>
                        </div>
                    <?PHP elseif ($smokerPref === false): ?>
                        <div class="text-icon">
                            <img src='../icons/FumerPasOk.png' class='img-width-20' alt=''>
                            <span>Je préfère ne pas voyager avec des fumeurs</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $musicPref = $driver->getMusicPreference();
                    if ($musicPref === true): ?>
                        <div class="text-icon">
                            <img src='../icons/MusiqueOk.png' class='img-width-20' alt=''>
                            <span>J'aime conduire en écoutant de la musique</span>
                        </div>
                    <?PHP elseif ($musicPref === false): ?>
                        <div class="text-icon">
                            <img src='../icons/MusiquePasOk.png' class='img-width-20' alt=''>
                            <span>Je préfère ne pas écouter de musique pendant que je conduis</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $speakerPref = $driver->getSpeakerPreference();
                    if ($speakerPref === true): ?>
                        <div class="text-icon">
                            <img src='../icons/speakOk.png' class='img-width-20' alt=''>
                            <span>Je discute volontiers avec mes passagers</span>
                        </div>
                    <?PHP elseif ($speakerPref === false): ?>
                        <div class="text-icon">
                            <img src='../icons/speakNotOk.png' class='img-width-20' alt=''>
                            <span>Je préfère me concentrer sur la route</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $foodPref = $driver->getFoodPreference();
                    if ($foodPref === true): ?>
                        <div class="text-icon">
                            <img src='../icons/foodOk.png' class='img-width-20' alt=''>
                            <span>La nourriture est autorisée dans la voiture </span>
                        </div>
                    <?PHP elseif ($foodPref === false): ?>
                        <div class="text-icon">
                            <img src='../icons/foodNotOk.png' class='img-width-20' alt=''>
                            <span>Pas de nourriture dans la voiture s'il vous plait</span>
                        </div>
                    <?php endif; ?>
                    <!--Others preferences-->
                    <?php
                    $addPref1 = $driver->getAddPref1();
                    if (isset($addPref1)): ?>
                        <div class="text-icon">
                            <img src='../icons/addPref.png' class='img-width-20' alt=''>
                            <span><?= $addPref1 ?></span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $addPref2 = $driver->getAddPref2();
                    if (isset($addPref2)): ?>
                        <div class="text-icon">
                            <img src='../icons/addPref.png' class='img-width-20' alt=''>
                            <span><?= $addPref2 ?></span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $addPref3 = $driver->getAddPref3();
                    if (isset($addPref3)): ?>
                        <div class="text-icon">
                            <img src='../icons/addPref.png' class='img-width-20' alt=''>
                            <span><?= $addPref3 ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!--RATING'S DRIVER BLOCK-->


        <section class="flex-column block-light-grey gap-12 block-driver-ratings">

            <div class="flex-column gap-8 item-center">
                <h3 class="text-green">Avis du chauffeur</h3>
                <div class="flex-row item-center gap-4" style="padding-left: 0px;">
                    <img src="../icons/EtoileJaune.png" class="img-width-20" alt="">
                    <span><?php $averageRating = $driver->getAverageRatings();
                    if ($averageRating !== null) {
                        echo htmlspecialchars($averageRating) . " / 5";
                    } ?>

                    </span>
                    <span class="font-size-very-small"><?= "(" . htmlspecialchars($driver->getNbRatings()) . " avis)" ?></span>
                </div>
            </div>


            <!--ratings list-->
            <?php $ratingsList = $driver->loadDriversRatingsInformations();
            foreach ($ratingsList as $rating): ?>

                <div class="flex-column gap-8">
                    <div class="flex-row flex-between">
                        <div class="flex-row item-center gap-4">
                            <img src="<?= displayPhoto($rating['photo']) ?>" alt="Photo de l'utilisateur" class="user-photo">
                            <span><?= htmlspecialchars($rating['pseudo']) ?></span>
                        </div>
                        <div style="padding-left: 0px;">
                            <img src="../icons/EtoileJaune.png" class="img-width-20" alt="">
                            <span><?= htmlspecialchars($rating['rating']) ?></span>

                        </div>
                    </div>
                    <p><?= htmlspecialchars(($rating['description'])) ?></p>
                    <span class="font-size-very-small italic"><?= htmlspecialchars(formatDateMonthAndYear($rating['created_at'])) ?></span>
                    <hr>
                </div>
            <?php endforeach ?>



        </section>

    </div>

</main>