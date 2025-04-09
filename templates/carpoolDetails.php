<main>

    <!--Travel's details and booking block-->

    <h1 class="pageTitle gras"><?= formatDateLong(htmlspecialchars($_SESSION['departure-date-search'])) ?? '' ?>
    </h1>

    <section class="travelDetailsBookingBlock">
        <div class="travelDetailsBlock">

            <div class="locationAndTimeTravel">

                <div class="timeLocationEllipse">
                    <div class="timeLocation">
                        <span><?= formatTime(htmlspecialchars($travel->getDepartureTime())) ?></span>
                        <span><?= htmlspecialchars($travel->getDepartureCity()) ?></span>
                    </div>

                    <div id="ellipse"></div>
                </div>

                <div class="durationLine">
                    <div class="travelDuration">
                        <?= htmlspecialchars($travel->travelDuration($travel->getDepartureTime(), $travel->getArrivalTime())) ?>
                    </div>
                    <div class="line"></div>
                </div>

                <div class="timeLocationEllipse">
                    <div id="ellipse"></div>
                    <div class="timeLocation">
                        <span><?= formatTime(htmlspecialchars($travel->getArrivalTime())) ?></span>
                        <span><?= htmlspecialchars($travel->getArrivalCity()) ?></span>
                    </div>

                </div>

            </div>

            <div class="seatsAndEco">
                <div> <?php $seatsAvailable = seatsAvailable($car->nbSeatsOfferedInACarpool($pdo, $travel->getCarId()), ($reservation->nbPassengerInACarpool($pdo, $travel->getIdTravel())));

                if ($seatsAvailable <= 1) {
                    echo htmlspecialchars($seatsAvailable) . " place restante";
                } else {
                    echo htmlspecialchars($seatsAvailable) . " places restantes";
                }

                ?>
                </div>
                <div>
                    <span class="criteriaEco"><?php
                    echo formatEco(($car->getElectric())) ?></span>
                </div>
            </div>

        </div>
        <div class="travelValidation">
            <div class="nbPassengerCredit">
                <div>1 passager</div>
                <div class="bold"><?php $travelPrice = $travel->getPrice();
                if ($travelPrice > 1) {
                    echo htmlspecialchars($travelPrice) . " crédits";
                } else {
                    echo htmlspecialchars($travelPrice) . " crédit";
                }
                ?> </div>
            </div>

            <button id="participate" class="btn action-btn" style="padding: 8px;"data-id="<?= htmlspecialchars($travel->getIdTravel()) ?>">
                <img src="../icons/Calendrier.png" class="imgFilterDate" alt="booking calendar icon">
                <span style="font-size: 16px">Participer au covoiturage</span>
            </button>
        </div>

    </section>

    <article class="thirdBlock">

        <!--Driver's details-->

        <section class="driversDescriptionPreferencesBlock">
            <div class="infoDriver">
                <img src="../icons/Femme1.jpg" class="photo" alt="photo de l'utilisateur">
                <div class="pseudoRating">
                    <span><?= htmlspecialchars($driver->getPseudo()) ?></span>
                    <div class="textIcon" style="padding-left: 0px;">
                        <img src="../icons/EtoileJaune.png" class="imgFilter" alt="">
                        <span><?php $averageRating = $driver->getAverageRatings();
                        if ($averageRating !== null) {
                            echo htmlspecialchars($averageRating);
                        } else {
                            echo "(0 avis)";
                        } ?> </span>
                    </div>
                </div>
            </div>

            <div class="carsDescriptionAndPreferences">


                <?php $travelDescription = $travel->getDescription();
                ;

                if ($travelDescription != null) {
                    ?>
                    <p class="removeMargins">
                        <?= htmlspecialchars($travelDescription); ?>
                    </p>
                <?php } ?>


                <div class="preferencesBlock">
                    <div class="bold">Véhicule</div>
                    <div class="textIcon">
                        <img src="../icons/Voiture.png" class="imgFilter" alt="">
                        <span><?php
                        echo htmlspecialchars($car->getBrand() . " " . $car->getModel() . " - " . $car->getColor());
                        if ($car->getElectric() === true) {
                            echo " - Electrique";
                        }
                        ?></span>
                    </div>
                </div>

                <div class="preferencesBlock">
                    <div class="bold">Préférences</div>
                    <?php
                    $petPref = $driver->getPetPreference();
                    if ($petPref === true): ?>
                        <div class='textIcon'>
                            <img src='../icons/AnimauxOk.png' class='imgFilter' alt=''>
                            <span>J'aime la compagnie des animaux</span>
                        </div>
                    <?php elseif ($petPref === false): ?>
                        <div class='textIcon'>
                            <img src='../icons/AnimauxPasOk.png' class='imgFilter' alt=''>
                            <span>Je préfère ne pas voyager avec des animaux</span>
                        </div>
                    <?php endif; ?>


                    <?php
                    $smokerPref = $driver->getSmokerPreference();
                    if ($smokerPref === true): ?>
                        <div class='textIcon'>
                            <img src='../icons/FumerOk.png' class='imgFilter' alt=''>
                            <span>La fumée ne me dérange pas</span>
                        </div>
                    <?PHP elseif ($smokerPref === false): ?>
                        <div class='textIcon'>
                            <img src='../icons/FumerPasOk.png' class='imgFilter' alt=''>
                            <span>Je préfère ne pas voyager avec des fumeurs</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $musicPref = $driver->getMusicPreference();
                    if ($musicPref === true): ?>
                        <div class='textIcon'>
                            <img src='../icons/MusiqueOk.png' class='imgFilter' alt=''>
                            <span>J'aime conduire en écoutant de la musique</span>
                        </div>
                    <?PHP elseif ($musicPref === false): ?>
                        <div class='textIcon'>
                            <img src='../icons/MusiquePasOk.png' class='imgFilter' alt=''>
                            <span>Je préfère ne pas écouter de musique pendant que je conduis</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $speakerPref = $driver->getSpeakerPreference();
                    if ($speakerPref === true): ?>
                        <div class='textIcon'>
                            <img src='../icons/speakOk.png' class='imgFilter' alt=''>
                            <span>Je discute volontiers avec mes passagers</span>
                        </div>
                    <?PHP elseif ($speakerPref === false): ?>
                        <div class='textIcon'>
                            <img src='../icons/speakNotOk.png' class='imgFilter' alt=''>
                            <span>Je préfère me concentrer sur la route</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $foodPref = $driver->getFoodPreference();
                    if ($foodPref === true): ?>
                        <div class='textIcon'>
                            <img src='../icons/foodOk.png' class='imgFilter' alt=''>
                            <span>La nourriture est autorisée dans la voiture </span>
                        </div>
                    <?PHP elseif ($foodPref === false): ?>
                        <div class='textIcon'>
                            <img src='../icons/foodNotOk.png' class='imgFilter' alt=''>
                            <span>Pas de nourriture dans la voiture s'il vous plait</span>
                        </div>
                    <?php endif; ?>
                    <!--Others preferences-->
                    <?php
                    $addPref1 = $driver->getAddPref1();
                    if (isset($addPref1)): ?>
                        <div class='textIcon'>
                            <img src='../icons/addPref.png' class='imgFilter' alt=''>
                            <span><?= $addPref1 ?></span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $addPref2 = $driver->getAddPref2();
                    if (isset($addPref2)): ?>
                        <div class='textIcon'>
                            <img src='../icons/addPref.png' class='imgFilter' alt=''>
                            <span><?= $addPref2 ?></span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $addPref3 = $driver->getAddPref3();
                    if (isset($addPref3)): ?>
                        <div class='textIcon'>
                            <img src='../icons/addPref.png' class='imgFilter' alt=''>
                            <span><?= $addPref3 ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!--RATING'S DRIVER BLOCK-->


        <section class="driverRatingBlock">

            <div class="driverRatingTitle">
                <span class="subtitle">Avis du chauffeur</span>
                <div class="driverRating" style="padding-left: 0px;">
                    <img src="../icons/EtoileJaune.png" class="imgFilter" alt="">
                    <span><?php $averageRating = $driver->getAverageRatings();
                    if ($averageRating !== null) {
                        echo htmlspecialchars($averageRating) . " / 5";
                    } ?>

                    </span>
                    <span
                        style="font-size: calc(100% - 4px);"><?= "(" . htmlspecialchars($driver->getNbRatings()) . " avis)" ?></span>
                </div>
            </div>


            <!--ratings list-->
            <?php $ratingsList = $driver->loadDriversRatingsInformations();
            foreach ($ratingsList as $rating): ?>

                <div class="rating">
                    <div class="photoPseudoRating">
                        <div class="photoPseudo">
                            <img src="../icons/Femme3.jpg" alt="Photo de l'utilisateur" class="userPhoto">
                            <span class="userPseudo"><?= htmlspecialchars($rating['pseudo']) ?></span>
                        </div>
                        <div class="driverRating" style="padding-left: 0px;">
                            <img src="../icons/EtoileJaune.png" class="imgFilter" alt="">
                            <span><?= htmlspecialchars($rating['rating']) ?></span>

                        </div>
                    </div>
                    <p class="removeMargins"><?= htmlspecialchars(($rating['description'])) ?></p>
                    <span class="ratingDate"><?= htmlspecialchars(formatDateMonthAndYear($rating['created_at'])) ?></span>
                    <hr>
                </div>
            <?php endforeach ?>



        </section>

    </article>

</main>
</body>