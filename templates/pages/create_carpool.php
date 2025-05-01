<style>
   

    .form-group {
        display: flex;
        flex-direction: column;
        justify-content :end;
    }

    .form-group label {
        margin-bottom: 6px;
        font-weight: bold;
    }

    .full-width-grid {
        grid-column: 1 / -1;
    }

   
</style>

<main class="gap-24">
    <div class="main-header">
        <h2 class="text-green">Proposer un covoiturage</h2>
        <a href= "<?= BASE_URL ?>/controllers /user_space.php?tab=carpools" class="btn return-btn">Retour à l'espace utilisateur</a>
    </div>

    <form action="../back/carpool/create.php" method="POST" class="half-separation">
        <div class="form-group full-width-grid">
            <label for="travelDate">Date du départ</label>
            <input type="date" id="travelDate" name="travelDate" required />
        </div>

        <div class="form-group" style="position: relative;">
            <label for="departure-city-search">Ville de départ</label>
            <input type="text" id="departure-city-search" name="departure-city-search" required />

            <div id="departure-suggestions" class="suggestions-list"></div>
        </div>

        <div class="form-group">
            <label for="travelDepartureTime">Heure de départ</label>
            <input type="time" id="travelDepartureTime" name="travelDepartureTime" required />
        </div>

        <div class="form-group" style="position: relative;">
            <label for="arrival-city-search">Ville d'arrivée</label>
            <input type="text" id="arrival-city-search" name="arrival-city-search" required />

            <div id="arrival-suggestions" class="suggestions-list"></div>
        </div>


        <div class="form-group">
            <label for="travelArrivalTime">Heure d'arrivée</label>
            <input type="time" id="travelArrivalTime" name="travelArrivalTime" required />
        </div>

        <div class="form-group">
            <label for="travelPrice">Prix pour une personne</label>
            <div class="flex-row">
                <input type="number" id="travelPrice" name="travelPrice" min="1" required />
                <span>crédits</span>
            </div>
        </div>

        <div class="flex-row">
            <img src="<?= BASE_URL ?>/icons/addPref.png" alt="info" class="img-width-20" />
            <span class="italic text-green font-size-small">Rappel : 2 crédits sont pris par la plateforme EcoRide</span>
        </div>

        <div class="form-group">
            <label for="carSelected">Voiture</label>
            <select id="carSelected" name="carSelected" required>
                <?php foreach ($carsOfDriver as $car): ?>
                    <option value="<?= $car['car_id'] ?>"><?= $car['name'] . " " . $car['car_model'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <button type="button" onclick="showPopupCar(event)" class="btn action-btn"
                style="background-color:inherit; border: 1.5px solid black; width:fit-content;">Autre voiture</button>
        </div>

        <div class="form-group full-width-grid">
            <label for="comment">Ajouter un commentaire (facultatif)</label>
            <textarea id="comment" name="comment" rows="4"></textarea>
        </div>

        <div class="btn bg-light-green full-width-grid">
            <input type="submit" value="Proposer le trajet"  />
        </div>
    </form>
    <?php include __DIR__ . "/../templates/components/popup/new_car.php";
    ?>
</main>


<!-- <main>
    <div class="flex-row flex-between">
        <h2 class="text-green text-bold">Proposer un covoiturage</h2>
        <a href= "<?= BASE_URL ?>/controllers /user_space.php?tab=carpools" class="btn"
            style="border: 2px solid var(--col-green); background-color:var(--col-light-grey);">Retourner à l'espace
            utilisateur</a>
    </div>

    <form action="../back/carpool/create.php" method="POST" class="block-column-g20" style="padding: 10px 0px;">
        <div class="flex-row">
            <label for="departure-date-search">Date du départ : </label>
            <div class="flex-row"
                style=" border: 1px solid var(--col-dark-grey);border-radius: var(--border-radius);padding: 4px; width: 120px;">
                <img class="img-pointer" src="<?= BASE_URL ?>/icons/Calendrier2.png" alt="Calendrier">
                <input type="date" id="departure-date-search" name="travelDate" class="date-field" style="width:200px;"
                    required>
            </div>
        </div>
        <div class="flex-row gap-24">
            <div style="position: relative;">
                <div class="flex-row">
                    <label for="departure-city-search">Ville de départ : </label>
                    <input type="text" id="departure-city-search" name="departure-city-search" class="text-field"
                        style="width:300px;" required>
                </div>
                <div id="departure-suggestions" class="suggestions-list"></div>

            </div>
            <div class="flex-row">
                <label for="travelDepartureTime">Heure de départ : </label>
                <input type="time" id="travelDepartureTime" name="travelDepartureTime" class="text-field" required>
            </div>
        </div>
        <div class="flex-row gap-24">
            <div style="position: relative;">
                <div class="flex-row">
                    <label for="arrival-city-search">Ville d'arrivée :</label>
                    <input type="text" id="arrival-city-search" name="arrival-city-search" class="text-field"
                        style="width:300px;" required>
                </div>
                <div id="arrival-suggestions" class="suggestions-list"></div>
            </div>
            <div class="flex-row">
                <label for="travelArrivalTime">Heure d'arrivée : </label>
                <input type="time" id="travelArrivalTime" name="travelArrivalTime" class="text-field" required>

            </div>
        </div>
        <div class="flex-row" style="gap:40px;">
            <div class="flex-row">
                <label for="travelPrice">Prix pour une personne : </label>
                <input type="number" id="travelPrice" name="travelPrice" class="short-field" min="1" required>
                <label for="travelPrice">crédits </label>
            </div>
            <div class="flex-row">
                <img src="<?= BASE_URL ?>/icons/addPref.png" class="img-width-20" alt="warningIcon">
                <span class="italic text-green font-size-small">Rappel : 2 crédits sont pris par
                    la plateforme EcoRide</span>
            </div>
        </div>
        <div class="flex-row gap-24">

            <div class="flex-row">
                <label for="carSelected">Voiture : </label>
                <select id="carSelected" name="carSelected" class="text-field" style="width:200px; height:30px;"
                    required>
                    <?php foreach ($carsOfDriver as $car): ?>
                        <option value="<?= $car['car_id'] ?>"><?= $car['name'] . " " . $car['car_model'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="button" onclick="showPopupCar(event)" class="btn action-btn"
                style="background-color:inherit; border: 1.5px solid black;">Autre voiture</button>
        </div>
        <div class="flex-row">
            <label for="comment">Ajouter un commentaire (facultatif) : </label>
        </div>
        <div>
            <textarea id="comment" name="comment" class="text-field" style="width: 400px; height:100px;"></textarea>
        </div>
        <div class="btn bg-light-green" style="width: fit-content;">
            <input type="submit" value="Proposer le trajet">
        </div>
    </form>
   
</main> -->