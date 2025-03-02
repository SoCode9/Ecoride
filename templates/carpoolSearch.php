<main>
    <!-- Find a carpool -->
    <div class="blockFindCarpool">
        <h1 class="pageTitle bold removeMargins">Rechercher un covoiturage</h1>
        <form class="searchBar" action="carpoolSearchIndex.php" method="POST">
            <input type="hidden" name="action" value="search"> <!--identify request-->

            <div class="searchField">
                <img class="imgFilter" src="../icons/Localisation(2).png" alt="lieu de départ">
                <input type="text" id="departure-city-search" name="departure-city-search" class="cityField"
                    placeholder="Ville de départ"
                    value="<?= htmlspecialchars($_SESSION['departure-city-search']) ?? '' ?>" required>
                <div id="departure-suggestions" class="suggestions-list"></div>
            </div>
            <span class="arrow">→</span>
            <div class="searchField">
                <img class="imgFilter" src="../icons/Localisation(2).png" alt="">
                <input type="text" id="arrival-city-search" name="arrival-city-search" class="cityField"
                    placeholder="Ville d'arrivée"
                    value="<?= htmlspecialchars($_SESSION['arrival-city-search']) ?? '' ?>" required>
                <div id="arrival-suggestions" class="suggestions-list"></div>
            </div>
            <div class="searchField">
                <img class="imgFilterDate" src="../icons/Calendrier2.png" alt="Calendrier">
                <input type="date" id="departure-date-search" name="departure-date-search" class="dateField"
                    placeholder="Date du départ"
                    value="<?= htmlspecialchars($_SESSION['departure-date-search']) ?? '' ?>" required>
            </div>

            <div class="searchButton">
                <img class="imgFilter" src="../icons/LoupeRecherche.png" alt="">
                <input type="submit" value="Rechercher" class="legendSearch bold">
            </div>
        </form>


    </div>

    <div class="filterAndDetails">

        <!--Search filters-->

        <div class="searchFilterBlock">
            <h2 class="subtitle removeMargins">Filtres de recherche</h2>
            <form class="filtersList" action="carpoolSearchIndex.php" method="POST">
                <input type="hidden" name="action" value="filters"> <!--identify filters-->

                <div class="filter">
                    <input id="eco" name="eco" type="checkbox" <?= isset($_SESSION['eco']) ? 'checked' : '' ?>>
                    <label for="eco">Voyage écologique</label>
                </div>
                <div class="filter">
                    <label for="max-price">Prix (max)</label>
                    <input type="number" id="max-price" name="max-price" class="numberField" min="1"
                        value="<?= isset($_SESSION['max-price']) ? htmlspecialchars($_SESSION['max-price']) : ''; ?>">
                </div>
                <div class="filter">
                    <label for="max-duration">Durée (max)</label>
                    <input type="number" id="max-duration" name="max-duration" class="numberField" min="1"
                        value="<?= isset($_SESSION['max-duration']) ? htmlspecialchars($_SESSION['max-duration']) : ''; ?>">
                    <label for="max-duration">h</label>
                </div>
                <div class="filter">
                    <label for="driver-rating-list">Note chauffeur (min) </label>

                    <select id="driver-rating-list" name="driver-rating-list" style="width: 50px;">
                        <optgroup>
                            <option value="none" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "none") ? 'selected' : ''; ?>></option>
                            <option value="5" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "5") ? 'selected' : ''; ?>>5</option>
                            <option value="4.5" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "4.5") ? 'selected' : ''; ?>>4.5</option>
                            <option value="4" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "4") ? 'selected' : ''; ?>>4</option>
                            <option value="3.5" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "3.5") ? 'selected' : ''; ?>>3.5</option>
                            <option value="3" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "3") ? 'selected' : ''; ?>>3</option>
                            <option value="2.5" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "2.5") ? 'selected' : ''; ?>>2.5</option>
                            <option value="2" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "2") ? 'selected' : ''; ?>>2</option>
                            <option value="1" <?= (isset($_SESSION['driver-rating-list']) && strval($_SESSION['driver-rating-list']) === "1") ? 'selected' : ''; ?>>1</option>
                        </optgroup>
                    </select>
                    <label for="driver-rating-list"><img src="../icons/EtoileJaune.png" alt="EtoileJaune"
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
                    <?= isset($_SESSION['departure-date-search']) ? "Départ le " . formatDate(htmlspecialchars($_SESSION['departure-date-search'])) : 'Aucune date sélectionnée'; ?>
                </span>
                <!-- remplacer par champ dynamique-->
                <img src="../icons/Suivant.png" alt="suivant" class="imgFilter">
            </div>

            <!--TRAVELS' SEARCHED BLOCK-->
            <div class="blocDetails">

                <?php
                if (isset($travelsSearched)) {
                    foreach ($travelsSearched as $t): ?>

                        <div class="travel">
                            <img src="../icons/Femme3.jpg" alt="Photo de l'utilisateur" class="photoUser">
                            <span class="pseudoUser"><?= htmlspecialchars($t['driver_pseudo']) ?></span>
                            <div class="driverRating">
                                <img src="../icons/EtoileJaune.png" alt="Etoile" class="imgFilter">
                                <span><?php
                                $driver = new Driver($pdo, $t['driver_id']);

                                echo htmlspecialchars($driver->getAverageRatings()) . " (" . htmlspecialchars($driver->getNbRatings()) . ")" ?></span>
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
                            <div class="criteriaEcoDiv">
                                <span class="criteriaEco"><?= formatEco(($t['car_electric'])) ?> </span>
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
                                <a href="carpoolDetailsIndex.php?id=<?= htmlspecialchars($t['id']) ?>"
                                    class="travelDetailsLegend">Détail</a>

                            </div>

                        </div>


                    <?php endforeach;
                } else {
                    echo "Oups.. Aucun covoiturage n'est proposé pour cette recherche.";
                    if (!empty($nextTravel)) {
                        // take the first result (if many travels)
                        $firstTravel = $nextTravel[0];

                        echo "<br><br>"; ?>
                        <div class="nextTravel"> Prochain itinéraire pour cette recherche le 
                            <?= htmlspecialchars($firstTravel['travel_date']) ?>
                        </div>
                    <?php } else {
                        echo "<br> <br>Aucun itinéraire futur ne correspond à cette recherche...";
                    }
                }
                ?>

            </div>

        </div>

    </div>



    </div>




</main>