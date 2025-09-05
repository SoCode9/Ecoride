<style>
    .form-group {
        display: flex;
        flex-direction: column;
        justify-content: end;
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
        <a href="<?= BASE_URL ?>/controllers/user_space.php?tab=carpools" class="btn return-btn">Retour à l'espace
            utilisateur</a>
    </div>

    <form action="../back/carpool/create.php" method="POST" class="half-separation">
        <div class="form-group full-width-grid">
            <label for="travel-date">Date du départ</label>
            <input type="date" id="travel-date" name="travel-date" required value="<?= htmlspecialchars($_SESSION['form_old']['travel-date'] ?? '') ?>" />
            <?php if (!empty($_SESSION['form_errors']['travel-date'])): ?>
                <p class="text-error"><?= htmlspecialchars($_SESSION['form_errors']['travel-date']) ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group" style="position: relative;">
            <label for="departure-city-search">Ville de départ</label>
            <input type="text" id="departure-city-search" name="departure-city-search" required value="<?= htmlspecialchars($_SESSION['form_old']['departure-city-search'] ?? '') ?>" />
            <?php if (!empty($_SESSION['form_errors']['departure-city-search'])): ?>
                <p class="text-error"><?= htmlspecialchars($_SESSION['form_errors']['departure-city-search']) ?></p>
            <?php endif; ?>
            <div id="departure-suggestions" class="suggestions-list"></div>
        </div>

        <div class="form-group">
            <label for="travel-departure-time">Heure de départ</label>
            <input type="time" id="travel-departure-time" name="travel-departure-time" required value="<?= htmlspecialchars($_SESSION['form_old']['travel-departure-time'] ?? '') ?>" />
            <?php if (!empty($_SESSION['form_errors']['travel-departure-time'])): ?>
                <p class="text-error"><?= htmlspecialchars($_SESSION['form_errors']['travel-departure-time']) ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group" style="position: relative;">
            <label for="arrival-city-search">Ville d'arrivée</label>
            <input type="text" id="arrival-city-search" name="arrival-city-search" required value="<?= htmlspecialchars($_SESSION['form_old']['arrival-city-search'] ?? '') ?>" />
            <?php if (!empty($_SESSION['form_errors']['arrival-city-search'])): ?>
                <p class="text-error"><?= htmlspecialchars($_SESSION['form_errors']['arrival-city-search']) ?></p>
            <?php endif; ?>
            <div id="arrival-suggestions" class="suggestions-list"></div>
        </div>


        <div class="form-group">
            <label for="travel-arrival-time">Heure d'arrivée</label>
            <input type="time" id="travel-arrival-time" name="travel-arrival-time" required value="<?= htmlspecialchars($_SESSION['form_old']['travel-arrival-time'] ?? '') ?>" />
            <?php if (!empty($_SESSION['form_errors']['travel-arrival-time'])): ?>
                <p class="text-error"><?= htmlspecialchars($_SESSION['form_errors']['travel-arrival-time']) ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="travel-price">Prix pour une personne</label>
            <div class="flex-row">
                <input type="number" id="travel-price" name="travel-price" min="2" required value="<?= htmlspecialchars($_SESSION['form_old']['travel-price'] ?? '') ?>" />
                <span>crédits</span>
                <?php if (!empty($_SESSION['form_errors']['travel-price'])): ?>
                    <p class="text-error"><?= htmlspecialchars($_SESSION['form_errors']['travel-price']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex-row">
            <img src="<?= BASE_URL ?>/icons/addPref.png" alt="info" class="img-width-20" />
            <span class="italic text-green font-size-small">Rappel : 2 crédits sont pris par la plateforme
                EcoRide</span>
        </div>

        <div class="form-group" id="car-field">
            <?php include __DIR__ . '/../components/lists/car_select.php'; ?>
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
            <input type="submit" value="Proposer le trajet" />
        </div>
    </form>
    <?php include __DIR__ . "/../../templates/components/popup/new_car.php";
    ?>
</main>
<?php unset($_SESSION['form_errors'], $_SESSION['form_old'], $_SESSION['error_message']); ?>