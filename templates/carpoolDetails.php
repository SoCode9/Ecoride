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

        <div class="thirdBlock">

            <!--Driver's details-->

            <section class="driversDescriptionPreferencesBlock">
                <div class="photoPseudoRating">
                    <img src="../icons/Femme1.jpg" class="photo" alt="photo de l'utilisateur">
                    <div class="pseudoRating">
                        <span><?= htmlspecialchars($driver->getPseudo()) ?></span>
                        <div class="textIcon" style="padding-left: 0px;">
                            <img src="../icons/EtoileJaune.png" class="imgFilter" alt="">
                            <span><?= htmlspecialchars($driver->getRating()) ?> </span>
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
                        
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>