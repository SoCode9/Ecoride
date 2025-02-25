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
                    <div class="ecoCriteria">
                        <span><?php
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
    </main>
</body>