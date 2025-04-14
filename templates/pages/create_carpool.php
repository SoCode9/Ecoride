<main>
    <section class="searchFilterBlock">

        <div class="flex-row flex-between">
            <h1 class="pageTitle bold removeMargins">Proposer un covoiturage</h1>
            <a href="../controllers/user_space.php" class="btn"
                style="border: 2px solid #4D9856; background-color:var(--bg-light-grey);">Retourner à l'espace
                utilisateur</a>
        </div>

        <form action="../back/carpool/create.php" method="POST" class="filtersList" style="gap: 20px;">
            <div class="filter">
                <label for="departure-date-search">Date du départ</label>
                <div class="filter"
                    style=" border: 1px solid #666666;border-radius: 15px;padding: 4px;font-size: 15px; background-color:white;width: 120px;">
                    <img class="imgFilterDate" src="../icons/Calendrier2.png" alt="Calendrier">
                    <input type="date" id="departure-date-search" name="travelDate" class="dateField"
                        style="width:200px;" required>
                </div>
            </div>
            <div class="filter" style="gap:20px;">
                <div style="position: relative;">
                    <div class="filter">
                        <label for="departure-city-search">Ville de départ : </label>
                        <input type="text" id="departure-city-search" name="departure-city-search" class="textField"
                            style="width:300px;" required>
                    </div>
                    <div id="departure-suggestions" class="suggestions-list"></div>

                </div>
                <div class="filter">
                    <label for="travelDepartureTime">Heure de départ : </label>
                    <input type="time" id="travelDepartureTime" name="travelDepartureTime" class="textField" required>
                </div>
            </div>
            <div class="filter" style="gap:20px;">
                <div style="position: relative;">
                    <div class="filter">
                        <label for="arrival-city-search">Ville d'arrivée :</label>
                        <input type="text" id="arrival-city-search" name="arrival-city-search" class="textField"
                            style="width:300px;" required>
                    </div>
                    <div id="arrival-suggestions" class="suggestions-list"></div>
                </div>
                <div class="filter">
                    <label for="travelArrivalTime">Heure d'arrivée : </label>
                    <input type="time" id="travelArrivalTime" name="travelArrivalTime" class="textField" required>

                </div>
            </div>
            <div class="filter" style="gap:40px;">
                <div class="filter">
                    <label for="travelPrice">Prix pour une personne : </label>
                    <input type="number" id="travelPrice" name="travelPrice" class="numberField" min="1" required>
                    <label for="travelPrice">crédits </label>
                </div>
                <div class="filter">
                    <img src="../icons/addPref.png" class="imgFilter" alt="warningIcon">
                    <span style="font-style: italic; font-size: 13px; color: #4D9856;">Rappel : 2 crédits sont pris par
                        la plateforme EcoRide</span>
                </div>
            </div>
            <div class="filter" style="gap:20px;">

                <div class="filter">
                    <label for="carSelected">Voiture : </label>
                    <select id="carSelected" name="carSelected" class="textField" style="width:200px;" required>
                        <?php foreach ($carsOfDriver as $car): ?>
                            <option value="<?= $car['car_id'] ?>"><?= $car['name'] . " " . $car['car_model'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" onclick="showPopup(event)" class="btn action-btn"
                    style="background-color:inherit; border: 1.5px solid black;">Autre voiture</button>
            </div>
            <div class="filter">
                <label for="comment">Ajouter un commentaire (facultatif) : </label>
            </div>
            <div>
                <textarea id="comment" name="comment" class="textField" style="width: 400px; height:100px;"></textarea>
            </div>
            <div class="btn bg-light-green" style="width: fit-content;">
                <input type="submit" value="Proposer le trajet">
            </div>
        </form>
        <?php include "../templates/components/new_car_popup.php";
        ?>
    </section>
</main>