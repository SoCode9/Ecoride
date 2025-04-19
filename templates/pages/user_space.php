<main class="user-info-carpools-block">
    <!-- User's informations -->
    <section class="flex-column block-border-grey gap-24">
        <!--header of this section-->
        <div class="flex-row item-center flex-between">
            <div class="flex-row item-center">
                <img src="<?= displayPhoto($connectedUser->getPhoto()) ?>" alt="Photo de l'utilisateur" class="photo">
                <div class="flex-column">
                    <span class="pseudo-user"><?php echo htmlspecialchars($connectedUser->getPseudo()) ?></span>
                    <?php if (($connectedUser->getIdRole() === 2) or ($connectedUser->getIdRole() === 3)): ?>
                        <div class="flex-row">
                            <img src="../icons/EtoileJaune.png" alt="Etoile" class="img-width-20">
                            <span>
                                <?= htmlspecialchars($connectedDriver->getAverageRatings()) . " (" . htmlspecialchars($connectedDriver->getNbRatings()) . ")" ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <button class="btn action-btn content-btn active" id="edit-button">Modifier le profil</button>
            <button class="btn action-btn content-btn " style="background-color : var(--col-light-green)"
                id="save-button">Sauvegarder le profil</button>

        </div>
        <div class="flex-row item-center hidden" id="edit-photo-icon">
            <button onclick="showPopup('popup-new-photo')"
                style="width: 30px; background-color: var(--col-orange); padding:4px 4px;" class="btn"><img
                    src="../icons/Modifier.png" alt="edit">
            </button>
            <span class="italic font-size-small ">Modifier la photo de profil</span>
        </div>



        <?php include "../templates/components/popup/new_photo.php";
        ?>
        <div class="flex-row flex-between">
            <span><?php echo htmlspecialchars($connectedUser->getMail()) ?></span>
            <span><?php echo htmlspecialchars($connectedUser->getCredit()) ?> crédits</span>
        </div>
        <div class="flex-column gap-8">
            <h3 class="text-green">Type d'utilisateur</h3>
            <div class="flex-row flex-between">
                <div class="flex-row">
                    <label for="role_passenger" class="radio-not-edit">passager</label>
                    <input type="radio" name="user_role" id="role_passenger" <?php if ($connectedUser->getIdRole() === 1) {
                        echo 'checked';
                    } ?> class="radio-not-edit">
                </div>
                <div class="flex-row">
                    <label for="role_driver" class="radio-not-edit">chauffeur</label>
                    <input type="radio" name="user_role" id="role_driver" <?php if ($connectedUser->getIdRole() === 2) {
                        echo 'checked';
                    } ?> class="radio-not-edit">
                </div>
                <div class="flex-row">
                    <label for="role_both" class="radio-not-edit">les deux</label>
                    <input type="radio" name="user_role" id="role_both" <?php if ($connectedUser->getIdRole() === 3) {
                        echo 'checked';
                    } ?> class="radio-not-edit">
                </div>
            </div>
        </div>

        <div class="scrollable-container flex-column gap-8">
            <!--Cars section-->
            <div class="flex-column gap-8 block-light-grey" style="padding: 16px;">
                <h3 class="text-green">Voitures</h3>

                <div id="car-container" class="flex-column gap-8" style="padding:10px 0px">
                    <?php include '../templates/components/lists/cars.php'; ?>
                </div>


                <div class="carForm hidden">
                    <hr>
                    <form id="car-form" class="ajax-form block-column-g20 " style="gap: 10px;">
                        <input type="hidden" name="action" value="formCar">

                        <div class="flex-row">
                            <label for="licence_plate">Plaque immatriculation : </label>
                            <input type="text" id="licence_plate" name="licence_plate" class="text-field"
                                placeholder="AA-000-AA" required>
                        </div>

                        <div class="flex-row">
                            <label for="first_registration_date">Date première immatriculation : </label>
                            <input type="date" id="first_registration_date" name="first_registration_date"
                                class="text-field" required>
                        </div>
                        <div class="flex-row">
                            <label for="brand">Marque : </label>
                            <select id="brand" class="text-field" name="brand" required>
                                <option value="">Sélectionner</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= htmlspecialchars($brand['id']); ?>">
                                        <?= htmlspecialchars($brand['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex-row">
                            <label for="model">Modèle : </label>
                            <input type="text" id="model" name="model" class="text-field" required>
                        </div>
                        <div class="flex-row">
                            <label for="electric">Electrique : </label>
                            <input type="radio" name="electric" value="yes" id="electric_yes" required>
                            <label for="electric_yes">oui</label>

                            <input type="radio" name="electric" value="no" id="electric_no" required>
                            <label for="electric_no">non</label>

                        </div>
                        <div class="flex-row">
                            <label for="color">Couleur : </label>
                            <input type="text" id="color" name="color" class="text-field" required>
                        </div>
                        <div class="flex-row">
                            <label for="nb_passengers">Nombre de passagers possible : </label>
                            <input type="number" id="nb_passengers" name="nb_passengers" class="numberField text-field"
                                style="width: 40px;" required>
                        </div>
                        <div class="btn bg-light-green" style="width:100px; align-self:self-end;">
                            <input type="submit" value="Enregistrer"></input>
                        </div>

                    </form>
                    <hr>
                </div>
                <button class="btn action-btn hidden" id="add_car_button">Ajouter une voiture</button>

            </div>

            <!--preferences section-->
            <div class="flex-column gap-8 block-light-grey" style="padding: 16px;">
                <h3 class="text-green">Préférences en tant que chauffeur</h3>
                <div class="flex-column gap-8" style="padding:10px 0px">
                    <span>Voyager avec des fumeurs ne me dérange pas</span>
                    <div class="flex-row flex-between">
                        <div class="flex-row">
                            <label for="smoke_yes" class="radio-not-edit">Oui</label>
                            <input type="radio" class="radio-not-edit" name="smoke_pref" id="smoke_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getSmokerPreference() === true)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="smoke_no" class="radio-not-edit">Non</label>
                            <input type="radio" class="radio-not-edit" name="smoke_pref" id="smoke_no" <?php if (isset($connectedDriver) && ($connectedDriver->getSmokerPreference() === false)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="smoke_undefined" class="radio-not-edit">Pas de préférence</label>
                            <input type="radio" class="radio-not-edit" name="smoke_pref" id="smoke_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getSmokerPreference() === null)) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>J'aime la compagnie des animaux</span>
                    <div class="flex-row flex-between">
                        <div class="flex-row">
                            <label for="pet_yes" class="radio-not-edit">Oui</label>
                            <input type="radio" class="radio-not-edit" name="pet_pref" id="pet_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getPetPreference() === true)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="pet_no" class="radio-not-edit">Non</label>
                            <input type="radio" class="radio-not-edit" name="pet_pref" id="pet_no" <?php if (isset($connectedDriver) && ($connectedDriver->getPetPreference() === false)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="pet_undefined" class="radio-not-edit">Pas de préférence</label>
                            <input type="radio" class="radio-not-edit" name="pet_pref" id="pet_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getPetPreference() === null)) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>La nourriture est autorisée dans la voiture</span>
                    <div class="flex-row flex-between">
                        <div class="flex-row">
                            <label for="food_yes" class="radio-not-edit">Oui</label>
                            <input type="radio" class="radio-not-edit" name="food_pref" id="food_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getFoodPreference() === true)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="food_no" class="radio-not-edit">Non</label>
                            <input type="radio" class="radio-not-edit" name="food_pref" id="food_no" <?php if (isset($connectedDriver) && ($connectedDriver->getFoodPreference() === false)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="food_undefined" class="radio-not-edit">Pas de préférence</label>
                            <input type="radio" class="radio-not-edit" name="food_pref" id="food_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getFoodPreference() === null)) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>Je discute volontiers avec mes passagers</span>
                    <div class="flex-row flex-between">
                        <div class="flex-row">
                            <label for="speak_yes" class="radio-not-edit">Oui</label>
                            <input type="radio" class="radio-not-edit" name="speak_pref" id="speak_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getSpeakerPreference() === true)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="speak_no" class="radio-not-edit">Non</label>
                            <input type="radio" class="radio-not-edit" name="speak_pref" id="speak_no" <?php if (isset($connectedDriver) && ($connectedDriver->getSpeakerPreference() === false)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="speek_undefined" class="radio-not-edit">Pas de préférence</label>
                            <input type="radio" class="radio-not-edit" name="speak_pref" id="speak_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getSpeakerPreference() === null)) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>J'aime conduire en écoutant de la musique</span>
                    <div class="flex-row flex-between">
                        <div class="flex-row">
                            <label for="music_yes" class="radio-not-edit">Oui</label>
                            <input type="radio" class="radio-not-edit" name="music_pref" id="music_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getMusicPreference() === true)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="music_no" class="radio-not-edit">Non</label>
                            <input type="radio" class="radio-not-edit" name="music_pref" id="music_no" <?php if (isset($connectedDriver) && ($connectedDriver->getMusicPreference() === false)) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="flex-row">
                            <label for="music_undefined" class="radio-not-edit">Pas de préférence</label>
                            <input type="radio" class="radio-not-edit" name="music_pref" id="music_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getMusicPreference() === null)) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <div id="pref-container">
                        <?php
                        include "../templates/components/lists/other_pref.php";
                        ?>
                    </div>

                </div>

                <div class="prefForm hidden">
                    <form action="" method="POST" id="pref-form" class="ajax-form block-column-g20" style="gap: 10px;">
                        <input type="hidden" name="action" value="formPref">

                        <hr>
                        <input type="text" placeholder="Entrez la préférence" name="new_pref" id="new_pref"
                            class="text-field" style="width:auto" required>

                        <div class="btn bg-light-green" style="width:100px; align-self:self-end;">
                            <input type="submit" value="Enregistrer">
                        </div>
                    </form>
                    <hr>
                </div>
                <button class="btn action-btn hidden" id="add_pref_button">Ajouter une préférence</button>

            </div>
        </div>


    </section>

    <!-- User's carpool -->

    <section class="flex-column block-light-grey">
        <div class="flex-row item-center flex-between">
            <h1 class="text-green">Mes covoiturages</h1>
            <?php if (($connectedUser->getIdRole() === 2 || $connectedUser->getIdRole() === 3) && $cars == !null): ?>
                <a class="btn action-btn" style="padding: 8px;" href="../controllers/create_carpool.php">Proposer un
                    covoiturage</a>
            <?php endif; ?>
        </div>

        <div class="tabs">
            <button class="btn tab-btn active" data-target="notStarted">En cours</button>
            <button class="btn tab-btn" data-target="completed">Terminés</button>
        </div>

        <div id="notStarted" class="tab-content active">
            <?php include '../templates/components/lists/carpools_not_started.php'; ?>
        </div>

        <div id="completed" class="tab-content">
            <?php include '../templates/components/lists/carpools_completed.php'; ?>
        </div>

    </section>
</main>