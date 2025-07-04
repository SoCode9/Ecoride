<main>
    <!-- Find a carpool -->
    <div class="flex-column gap-24 block-light-grey">
        <h2 class="text-green text-bold">Rechercher un covoiturage</h2>
        <form class="block-search" action="carpool_search.php" method="POST">
            <input type="hidden" name="action" value="search"> <!--identify request-->

            <div class="flex-row search-field">
                <img class="img-width-20" src="<?= BASE_URL ?>/icons/Localisation(2).png" alt="lieu de départ">
                <input type="text" id="departure-city-search" name="departure-city-search" class="font-size-small text-breakable  "
                     placeholder="Ville de départ"
                    value="<?= isset($_SESSION['departure-city-search']) ? htmlspecialchars($_SESSION['departure-city-search']) : '' ?>"
                    required>
                <div id="departure-suggestions" class="suggestions-list"></div>
            </div>
            <span class="flex-row">→</span>
            <div class="flex-row search-field">
                <img class="img-width-20" src="<?= BASE_URL ?>/icons/Localisation(2).png" alt="">
                <input type="text" id="arrival-city-search" name="arrival-city-search" class="font-size-small text-breakable  "
                 placeholder="Ville d'arrivée"
                    value="<?= isset($_SESSION['arrival-city-search']) ? htmlspecialchars($_SESSION['arrival-city-search']) : '' ?>"
                    required>
                <div id="arrival-suggestions" class="suggestions-list"></div>
            </div>
            <div class="flex-row search-field">
                <img class="img-pointer" src="<?= BASE_URL ?>/icons/Calendrier2.png" alt="Calendrier">
                <input type="date" id="departure-date-search" name="departure-date-search"
                    class="date-field font-size-small  " style="width:110px;"
                    value="<?= isset($_SESSION['departure-date-search']) ? htmlspecialchars($_SESSION['departure-date-search']) : '' ?>"
                    required>
            </div>
            <div class="flex-row" style="width:100%;">
                <div class="btn bg-light-green" id="search-btn">
                            <img class="img-width-20" src="<?= BASE_URL ?>/icons/LoupeRecherche.png" alt="">
                            <input type="submit" value="Rechercher">
                        </div>
                        <div id="filter-icon" class="inactive" style="display: none;" >
                            <a href="#" id="filter-toggle" class="btn" style="border-radius: 50%; width: fit-content; height: 35px; justify-self:right;">
                                <img class="img-width-20" src="<?= BASE_URL ?>/icons/Filtre.png" alt="">
                            </a>
                        </div>
            </div>
          
        </form>
    </div>

   
    
    <div class="block-filter-details">

        <!--Search filters-->

        <div class="flex-column block-light-grey" id="filter-block">
            <h3 class="text-green" style="padding-bottom: 24px;">Filtres de recherche</h3>
            <form class="block-column-g20" action="carpool_search.php" method="POST">
                <input type="hidden" name="action" value="filters"> <!--identify filters-->

                <div class="flex-row">
                    <input id="eco" name="eco" type="radio" <?= isset($_SESSION['eco']) ? 'checked' : '' ?>>
                    <label for="eco">Voyage écologique</label>
                </div>
                <div class="flex-row">
                    <label for="max-price">Prix (max)</label>
                    <input type="number" id="max-price" name="max-price" class="short-field" min="1"
                        value="<?= isset($_SESSION['max-price']) ? htmlspecialchars($_SESSION['max-price']) : ''; ?>">
                </div>
                <div class="flex-row">
                    <label for="max-duration">Durée (max)</label>
                    <input type="number" id="max-duration" name="max-duration" class="short-field" min="1"
                        value="<?= isset($_SESSION['max-duration']) ? htmlspecialchars($_SESSION['max-duration']) : ''; ?>">
                    <label for="max-duration">h</label>
                </div>
                <div class="flex-row">
                    <label for="driver-rating-list">Note chauffeur (min) </label>

                    <select id="driver-rating-list" name="driver-rating-list" class="short-field">
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
                    <label for="driver-rating-list"><img src="<?= BASE_URL ?>/icons/EtoileJaune.png" alt="EtoileJaune"
                            class="img-width-20"></label>
                </div>
                <div class="btn bg-light-green">
                    <input type="submit" value="Appliquer les filtres">

                </div>
                <button type="submit" name="action" value="reset_filters" class="btn col-back-grey-btn">Réinitialiser
                    les
                    filtres</button>
            </form>

        </div>
        <div class="flex-column">
            <span class="flex-row flex-center text-bold font-size-big">
                <?= isset($_SESSION['departure-date-search']) ? "Départ le " . formatDateLong(htmlspecialchars($_SESSION['departure-date-search'])) : 'Aucune date sélectionnée'; ?>
            </span>

            <!--TRAVELS' SEARCHED BLOCK-->
            <div class="flex-column gap-12 pad-20 pad-10-ss grid-auto-columns">

                <?php
                if (!empty($travelsSearched)) {
                    foreach ($travelsSearched as $t): ?>
                        <div class="travel"
                            onclick="window.location.href='carpool_details.php?id=<?= htmlspecialchars($t['id']) ?>'"
                            <?php if (isset($_SESSION['user_id']) && ($t['driver_id'] === $_SESSION['user_id'])) {
                                echo " style='border:2px solid var(--col-green);cursor:pointer;'";
                            } else {
                                echo " style ='cursor:pointer;'";
                            } ?>>

                            <?php
                            $seatsAvailable = seatsAvailable(
                                $car->getSeatsOfferedByCar($t['car_id']),
                                $reservation->countPassengers( $t['id'])
                            );
                            if ($seatsAvailable === 0): ?>
                                <span class="watermark-complet">Complet</span>
                            <?php endif; ?>

                            <div class="user-header-mobile">
                                <div class="photo-user-container" style="justify-self:center;">
                                    <img src="<?= displayPhoto($t['driver_photo']) ?>" alt="Photo de l'utilisateur"
                                        class="photo-user">
                                </div>
                                <div class="user-info-mobile"> 
                                    <span class="pseudo-user"><?= htmlspecialchars($t['driver_pseudo']) ?></span>
                                    <div class="driver-rating">                                        
                                        <div class="flex-row font-size-very-small">
                                            <?php
                                            $driver = new Driver($pdo, $t['driver_id']);                                            
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
                           </div>
                            <span class="date-travel">Départ à <?= htmlspecialchars($t['travel_departure_time']) ?>
                            </span>
                            <span class="hours-travel">Arrivée à
                                <?= htmlspecialchars($t['travel_arrival_time']) ?></span>
                            <span class="seats-available" id="seats-bs">Encore
                                <?php
                                if ($seatsAvailable > 1) {
                                    echo $seatsAvailable . " places";
                                } else {
                                    echo $seatsAvailable . " place";
                                }
                                ?>
                            </span>
                               
                            </span> 
                            <div class="criteria-eco-div">
                                <span class="criteria-eco"><?= formatEco(($t['car_electric'])) ?> </span>
                            </div>
                    
                            <span class="travel-price text-bold">
                                <?php
                                $trajetPrice = htmlspecialchars($t['travel_price']);
                                if ($trajetPrice > 1) {
                                    echo $trajetPrice . " crédits";
                                } else {
                                    echo $trajetPrice . " crédit";
                                }
                                ?></span>
                        </div>


                    <?php endforeach;
                } elseif (isset($_POST['action'])) {
                    echo "Oups.. Aucun covoiturage n'est proposé pour cette recherche.";
                }

                if (!empty($nextTravelDate)) {
                    // Take the first travel found 
                    $firstTravel = $nextTravelDate[0];

                    echo "<br><br>"; ?>

                    <!-- Form to restart search with new date -->
                    <form method="POST" action="carpool_search.php">
                        <input type="hidden" name="action" value="search">
                        <input type="hidden" name="departure-date-search"
                            value="<?= htmlspecialchars($firstTravel['travel_date']) ?>">
                        <input type="hidden" name="departure-city-search"
                            value="<?= htmlspecialchars($departureCitySearch) ?>">
                        <input type="hidden" name="arrival-city-search" value="<?= htmlspecialchars($arrivalCitySearch) ?>">
                        <input type="hidden" name="eco" value="<?= htmlspecialchars($eco) ?>">
                        <input type="hidden" name="max-price" value="<?= htmlspecialchars($maxPrice) ?>">
                        <input type="hidden" name="max-duration" value="<?= htmlspecialchars($maxDuration) ?>">
                        <input type="hidden" name="driver-rating-list" value="<?= htmlspecialchars($driverRating) ?>">

                        <button type="submit" class="btn bg-very-light-green" style="padding: 10px;">Prochain itinéraire
                            pour cette recherche le
                            <?= htmlspecialchars(formatDateLong($firstTravel['travel_date'])) ?></button>
                    </form>
                <?php }
                ?>

            </div>

        </div>

    </div>



    </div>




</main>