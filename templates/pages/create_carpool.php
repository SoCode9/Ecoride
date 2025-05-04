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
        <a href= "<?= BASE_URL ?>/controllers/user_space.php?tab=carpools" class="btn return-btn">Retour à l'espace utilisateur</a>
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
    <?php include __DIR__ . "/../../templates/components/popup/new_car.php";
    ?>
</main>