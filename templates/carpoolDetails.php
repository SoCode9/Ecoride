<head>

    <title>Détail du covoiturage</title>

</head>

<body>

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
                    <div> <?php $placesAvailable = $travel->getAvailableSeats();
                    if ($placesAvailable === 1) {
                        echo htmlspecialchars($placesAvailable) . " place restante";
                    } else {
                        echo htmlspecialchars($placesAvailable) . " places restantes";
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

                <button class="participateButton"> <!--Ajouter le bouton et le lien-->
                    <img src="../icons/Calendrier.png" class="imgFilterDate" alt="calendrier de réservation">
                    <span style="font-size: 16px;">Participer au covoiturage</span>
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
                        if ($petPref !== null) {
                            echo $petPref;
                        }
                        ?>
                        <?php
                        $smokerPref = $driver->getSmokerPreference();
                        if ($smokerPref !== null) {
                            echo $smokerPref;
                        }
                        ?>
                        <?php
                        $musicPref = $driver->getMusicPreference();
                        if ($musicPref !== null) {
                            echo $musicPref;
                        }
                        ?>
                        <?php
                        $speakerPref = $driver->getSpeakerPreference();
                        if ($speakerPref !== null) {
                            echo $speakerPref;
                        }
                        ?>
                        <?php
                        $foodPref = $driver->getFoodPreference();
                        if ($foodPref !== null) {
                            echo $foodPref;
                        }
                        ?>
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
                        <span
                            class="ratingDate"><?= htmlspecialchars(formatDateMonthAndYear($rating['created_at'])) ?></span>
                        <div class="lineSplitter"></div>
                    </div>
                <?php endforeach ?>



            </section>

        </article>

    </main>
</body>