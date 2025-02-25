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
                            <span><?= formatTime(htmlspecialchars($travel['travel_departure_time'])) ?></span>
                            <span><?= htmlspecialchars($travel['travel_departure_city']) ?></span>
                        </div>

                        <div id="ellipse"></div>
                    </div>

                    <div class="durationLine">
                        <div class="travelDuration">
                            <?= htmlspecialchars($travelInstance->travelDuration($travel['travel_departure_time'], $travel['travel_arrival_time'])) ?>
                        </div>
                        <div class="line"></div>
                    </div>

                    <div class="timeLocationEllipse">
                        <div id="ellipse"></div>
                        <div class="timeLocation">
                            <span><?= formatTime(htmlspecialchars($travel['travel_arrival_time'])) ?></span>
                            <span><?= htmlspecialchars($travel['travel_arrival_city']) ?></span>
                        </div>

                    </div>

                </div>

                <div class="seatsAndEco">
                    <div> <?php $placesAvailable = placesAvailable(placesOfferedNb: (int) $travel['places_offered'], placesAllocatedNb: (int) $travel['places_allocated']);
                    if ($placesAvailable === 1) {
                        echo htmlspecialchars($placesAvailable) . " place restante";
                    } else {
                        echo htmlspecialchars($placesAvailable) . " places restantes";
                    }

                    ?>
                    </div>
                    <div >
                        <span class="criteriaEco"><?php
                        echo formatEco(htmlspecialchars($travel['car_electric'])) ?></span>
                    </div>
                </div>

            </div>
            <div class="travelValidation">
                <div class="nbPassengerCredit">
                    <div>1 passager</div>
                    <div class="bold"><?php $travelPrice = $travel['travel_price'];
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
                        <span><?= htmlspecialchars($travel['pseudo']) ?></span>
                        <div class="textIcon" style="padding-left: 0px;">
                            <img src="../icons/EtoileJaune.png" class="imgFilter" alt="">
                            <span><?= htmlspecialchars($travel['driver_note']) ?> </span>
                        </div>
                    </div>
                </div>

                <div class="carsDescriptionAndPreferences">


                    <?php $travelDescription = $travel['travel_description'];

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
                            <span>LEXUS RZ 450E - Vert foncé - Electrique</span>
                        </div>
                    </div>

                    <div class="preferencesBlock">
                        <div class="bold">Préférences</div>
                        <div class="textIcon">
                            <img src="../icons/AnimauxOk.png" class="imgFilter" alt="">
                            <span>J'aime la compagnie des animaux</span>
                        </div>
                        <div class="textIcon">
                            <img src="../icons/FumerPasOk.png" class="imgFilter" alt="">
                            <span>Je préfère ne pas voyager avec des fumeurs</span>
                        </div>
                        <div class="textIcon">
                            <img src="../icons/Musique.png" class="imgFilter" alt="">
                            <span>J'aime conduire en écoutant de la musique</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>