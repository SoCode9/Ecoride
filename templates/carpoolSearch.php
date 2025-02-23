<head>

    <title>Covoiturages</title>

    <script src="CovoituragesRecherche.js" defer></script> <!--ADD THE JS FILE (FOR CALENDAR)-->
</head>

<body>

    <main>
        <!-- Find a carpool -->
        <div class="blockFindCarpool">
            <h1 class="pageTitle bold removeMargins">Rechercher un covoiturage</h1>
            <form class="searchBar" action="carpoolSearchIndex.php" method="POST">
                <div class="searchField">
                    <img class="imgFilter" src="../icons/Localisation(2).png" alt="lieu de départ">
                    <input type="text" id="departure-city-search" name="departure-city-search" class="cityField"
                        placeholder="Ville de départ"
                        value="<?= isset($_POST['departure-city-search']) ? htmlspecialchars($_POST['departure-city-search']) : ''; ?>"
                        required>

                </div>
                <span class="arrow">→</span>
                <div class="searchField">
                    <img class="imgFilter" src="../icons/Localisation(2).png" alt="">
                    <input type="text" id="departure-city-search" name="arrival-city-search" class="cityField"
                        placeholder="Ville d'arrivée"
                        value="<?= isset($_POST['departure-city-search']) ? htmlspecialchars($_POST['arrival-city-search']) : ''; ?>"
                        required>

                </div>
                <div class="searchField">
                    <img class="imgFilterDate" src="../icons/Calendrier2.png" alt="Calendrier">
                    <input type="date" id="departure-date-search" name="departure-date-search" class="dateField"
                        placeholder="Date du départ"
                        value="<?= isset($_POST['departure-date-search']) ? htmlspecialchars($_POST['departure-date-search']) : ''; ?>"
                        required>
                </div>

                <div class="searchButton">
                    <img class="imgFilter" src="../icons/LoupeRecherche.png" alt="">
                    <input type="submit" value="Rechercher" class="legendSearch bold">
                </div>
            </form>
        </div>

        <div class="filterAndDetails">

            <!--Filtres de recherche-->

            <div class="searchFilterBlock">
                <h2 class="subtitle removeMargins">Filtres de recherche</h2>
                <form class="filtersList">
                    <div class="filter">
                        <input id="eco" type="checkbox">
                        <label for="eco">Voyage écologique</label>
                    </div>
                    <div class="filter">
                        <label for="maxPrice">Prix maximum</label>
                        <input type="number" id="maxPrice" class="numberField" min="0">
                    </div>
                    <div class="filter">
                        <label for="maxDuration">Durée maximale</label>
                        <input type="number" id="maxDuration" class="numberField" min="0">
                        <label for="maxDuration">h</label>
                    </div>
                    <div class="filter">
                        <label for="noteDriverList">Note chauffeur </label>

                        <select name="noteDriverList" id="noteDriverList" style="width: 50px;">
                            <optgroup>
                                <option value="none"></option>
                                <option value="5">5</option>
                                <option value="4.5">4,5</option>
                                <option value="4">4</option>
                                <option value="3.5">3,5</option>
                                <option value="3">3</option>
                                <option value="2.5">2.5</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </optgroup>
                        </select>
                        <label for="noteDriverList"><img src="../icons/EtoileJaune.png" alt="EtoileJaune"
                                class="imgFilter"></label>
                    </div>
                    <div class="searchButton">
                        <input type="submit" value="Appliquer les filtres" class="legendSearch">
                    </div>
                </form>

            </div>
            <div class="daySelectedAndDetails">
                <div class="daySelected bold">
                    <img src="../icons/Precedentv3.png" alt="précédent" class="imgFilter">
                    <span class="daySelectedLegend">
                        <?= isset($_POST['departure-date-search']) ? "Départ le " . formatDate(htmlspecialchars($_POST['departure-date-search'])) : 'Aucune date sélectionnée'; ?>
                    </span>
                    <!-- remplacer par champ dynamique-->
                    <img src="../icons/Suivant.png" alt="suivant" class="imgFilter">
                </div>

                <!--BLOC RESULTATS DES TRAJETS-->
                <div class="blocDetails">

                    <?php
                    if (isset($travelsSearched)) {
                        foreach ($travelsSearched as $t): ?>

                            <div class="travel">
                                <img src="../icons/Femme3.jpg" alt="Photo de l'utilisateur" class="photoUser">
                                <span class="pseudoUser"><?= htmlspecialchars($t['driver_pseudo']) ?></span>
                                <div class="noteDriver">
                                    <img src="../icons/EtoileJaune.png" alt="Etoile" class="imgFilter">
                                    <span><?= htmlspecialchars($t['driver_note']) ?></span>
                                </div>
                                <span class="dateTravel">Départ à <?= htmlspecialchars($t['travel_departure_time']) ?>
                                </span>
                                <span class="hoursTravel">Arrivée à <?= htmlspecialchars($t['travel_arrival_time']) ?></span>
                                <span class="placesAvailable">Encore
                                    <?php
                                    $placesAvailable = placesAvailable($t['places_offered'], $t['places_allocated']);
                                    if ($placesAvailable > 1) {
                                        echo $placesAvailable . " places";
                                    } else {
                                        echo $placesAvailable . " place";
                                    }
                                    ?>

                                </span>
                                <div class="criteriaEco">
                                    <span><?= formatEco(htmlspecialchars($t['car_electric'])) ?> </span>
                                </div>
                                <span class="travelPrice gras">
                                    <?php
                                    $trajetPrice = htmlspecialchars($t['travel_price']);
                                    if ($trajetPrice > 1) {
                                        echo $trajetPrice . " crédits";
                                    } else {
                                        echo $trajetPrice . " crédit";
                                    }
                                    ?></span>
                                <div class="seeDetailTrajet">
                                    <img class="imgFilter" src="../icons/LoupeRecherche.png" alt="">
                                    <a href="DetailCovoiturage.html" class="travelDetailsLegend">Détail</a>

                                </div>

                            </div>


                        <?php endforeach;
                    } else {
                        echo "Oups.. Aucun covoiturage n'est proposé pour cette recherche.";
                    }
                    ?>

                </div>

            </div>

        </div>



        </div>




    </main>





    <div id="footer"></div>




    <script>

        document.getElementById('header').innerHTML = fetch('enTete.html')

            .then(response => response.text())

            .then(data => document.getElementById('header').innerHTML = data);

        document.getElementById('footer').innerHTML = fetch('piedPage.html')

            .then(response => response.text())

            .then(data => document.getElementById('footer').innerHTML = data);

    </script>

</body>



</html>