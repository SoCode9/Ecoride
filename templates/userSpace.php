<main class="userInfoAndCarpoolBlock">
    <!-- User's informations -->
    <section class="userInformationsBlock">
        <!--header of this section-->
        <div class="headerUserInfo">
            <div class="photoPseudoRating">
                <img src="../icons/Femme3.jpg" alt="Photo de l'utilisateur" class="photoConnectedUser">
                <div class="pseudoAndRating">
                    <span class="pseudoUser"><?php echo htmlspecialchars($connectedUser->getPseudo()) ?></span>
                    <?php if (($connectedUser->getIdRole() === 2) or ($connectedUser->getIdRole() === 3)): ?>
                        <div class="driverRating">
                            <img src="../icons/EtoileJaune.png" alt="Etoile" class="imgFilter">
                            <span>
                                <?= htmlspecialchars($connectedDriver->getAverageRatings()) . " (" . htmlspecialchars($connectedDriver->getNbRatings()) . ")" ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $editMode = isset($_GET['mode']) && $_GET['mode'] === 'edit';
            ?>

            <button class="seeDetailTrajet button-content <?= !$editMode ? 'active' : 'hidden' ?>" id="edit-button">
                Modifier le profil
            </button>

            <button class="seeDetailTrajet button-content <?= $editMode ? 'active' : 'hidden' ?>" id="save-button">
                Sauvegarder le profil
            </button>
        </div>
        <div class="mailAndCredits">
            <span><?php echo htmlspecialchars($connectedUser->getMail()) ?></span>
            <span><?php echo htmlspecialchars($connectedUser->getCredit()) ?> crédits</span>
        </div>
        <div class="subTitleAndContent">
            <h2 class="subTitleGreen">Type d'utilisateur</h2>
            <div class="treeRadioButton">
                <div class="filter">
                    <label for="role_passenger">passager</label>
                    <input type="radio" name="user_role" id="role_passenger" <?php if ($connectedUser->getIdRole() === 1) {
                        echo 'checked';
                    } ?> class="radioNotEdit">
                </div>
                <div class="filter">
                    <label for="role_driver">chauffeur</label>
                    <input type="radio" name="user_role" id="role_driver" <?php if ($connectedUser->getIdRole() === 2) {
                        echo 'checked';
                    } ?> class="radioNotEdit">
                </div>
                <div class="filter">
                    <label for="role_both">les deux</label>
                    <input type="radio" name="user_role" id="role_both" <?php if ($connectedUser->getIdRole() === 3) {
                        echo 'checked';
                    } ?> class="radioNotEdit">
                </div>
            </div>
        </div>

        <?php /* if ($connectedUser->getIdRole() !== 1): */ ?>
        <div class="scrollable-container subTitleAndContent">
            <!--Cars section-->
            <div class="subTitleAndContent greyBlock">
                <h2 class="subTitleGreen">Voitures</h2>

                <div id="car-container">
                    <!-- 🚀 Cette section sera mise à jour avec AJAX -->
                    <?php include '../templates/load_cars.php'; ?>
                </div>


                <div class="carForm hidden">
                    <hr>
                    <form id="car-form" class="filtersList" style="gap: 10px;">
                        <input type="hidden" name="action" value="formCar">
                        <input type="hidden" name="mode" value="edit"> <!-- Champ caché -->

                        <div class="filter">
                            <label for="licence_plate">Plaque immatriculation : </label>
                            <input type="text" id="licence_plate" name="licence_plate" class="textField"
                                placeholder="AA-000-AA" required>
                        </div>

                        <div class="filter">
                            <label for="first_registration_date">Date première immatriculation : </label>
                            <input type="date" id="first_registration_date" name="first_registration_date"
                                class="textField" required>
                        </div>
                        <div class="filter">
                            <label for="brand">Marque : </label>
                            <select id="brand" class="textField" name="brand" required>
                                <option value="">Sélectionner</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= htmlspecialchars($brand['id']); ?>">
                                        <?= htmlspecialchars($brand['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter">
                            <label for="model">Modèle : </label>
                            <input type="text" id="model" name="model" class="textField" required>
                        </div>
                        <div class="filter">
                            <label for="electric">Electrique : </label>
                            <input type="radio" name="electric" id="electric_yes" required>
                            <label for="electric_yes">oui</label>

                            <input type="radio" name="electric" id="electric_no" required>
                            <label for="electric_no">non</label>

                        </div>
                        <div class="filter">
                            <label for="color">Couleur : </label>
                            <input type="text" id="color" name="color" class="textField" required>
                        </div>
                        <div class="filter">
                            <label for="nb_passengers">Nombre de passagers possible : </label>
                            <input type="number" id="nb_passengers" name="nb_passengers" class="numberField textField"
                                style="width: 40px;" required>
                        </div>
                        <input type="submit" value="Enregistrer" class="searchButton"
                            style="width:100px; align-self:self-end;"></input>
                    </form>
                    <hr>
                </div>
                <button class="seeDetailTrajet hidden" id="add_car_button">Ajouter une voiture</button>

            </div>

            <!--preferences section-->
            <div class="subTitleAndContent greyBlock">
                <h2 class="subTitleGreen">Préférences en tant que chauffeur</h2>

                <span>Voyager avec des fumeurs ne me dérange pas</span>
                <div class="treeRadioButton">
                    <div class="filter">
                        <label for="smoke_yes">Oui</label>
                        <input type="radio" class="radioNotEdit" name="smoke_pref" id="smoke_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getSmokerPreference() === true)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="smoke_no">Non</label>
                        <input type="radio" class="radioNotEdit" name="smoke_pref" id="smoke_no" <?php if (isset($connectedDriver) && ($connectedDriver->getSmokerPreference() === false)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="smoke_undefined">Pas de préférence</label>
                        <input type="radio" class="radioNotEdit" name="smoke_pref" id="smoke_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getSmokerPreference() === null)) {
                            echo 'checked';
                        } ?>>
                    </div>
                </div>
                <hr>
                <span>J'aime la compagnie des animaux</span>
                <div class="treeRadioButton">
                    <div class="filter">
                        <label for="pet_yes">Oui</label>
                        <input type="radio" class="radioNotEdit" name="pet_pref" id="pet_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getPetPreference() === true)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="pet_no">Non</label>
                        <input type="radio" class="radioNotEdit" name="pet_pref" id="pet_no" <?php if (isset($connectedDriver) && ($connectedDriver->getPetPreference() === false)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="pet_undefined">Pas de préférence</label>
                        <input type="radio" class="radioNotEdit" name="pet_pref" id="pet_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getPetPreference() === null)) {
                            echo 'checked';
                        } ?>>
                    </div>
                </div>
                <hr>
                <span>La nourriture est autorisée dans la voiture</span>
                <div class="treeRadioButton">
                    <div class="filter">
                        <label for="food_yes">Oui</label>
                        <input type="radio" class="radioNotEdit" name="food_pref" id="food_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getFoodPreference() === true)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="food_no">Non</label>
                        <input type="radio" class="radioNotEdit" name="food_pref" id="food_no" <?php if (isset($connectedDriver) && ($connectedDriver->getFoodPreference() === false)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="food_undefined">Pas de préférence</label>
                        <input type="radio" class="radioNotEdit" name="food_pref" id="food_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getFoodPreference() === null)) {
                            echo 'checked';
                        } ?>>
                    </div>
                </div>
                <hr>
                <span>Je discute volontiers avec mes passagers</span>
                <div class="treeRadioButton">
                    <div class="filter">
                        <label for="speak_yes">Oui</label>
                        <input type="radio" class="radioNotEdit" name="speak_pref" id="speak_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getSpeakerPreference() === true)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="speak_no">Non</label>
                        <input type="radio" class="radioNotEdit" name="speak_pref" id="speak_no" <?php if (isset($connectedDriver) && ($connectedDriver->getSpeakerPreference() === false)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="speek_undefined">Pas de préférence</label>
                        <input type="radio" class="radioNotEdit" name="speak_pref" id="speak_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getSpeakerPreference() === null)) {
                            echo 'checked';
                        } ?>>
                    </div>
                </div>
                <hr>
                <span>J'aime conduire en écoutant de la musique</span>
                <div class="treeRadioButton">
                    <div class="filter">
                        <label for="music_yes">Oui</label>
                        <input type="radio" class="radioNotEdit" name="music_pref" id="music_yes" <?php if (isset($connectedDriver) && ($connectedDriver->getMusicPreference() === true)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="music_no">Non</label>
                        <input type="radio" class="radioNotEdit" name="music_pref" id="music_no" <?php if (isset($connectedDriver) && ($connectedDriver->getMusicPreference() === false)) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="filter">
                        <label for="music_undefined">Pas de préférence</label>
                        <input type="radio" class="radioNotEdit" name="music_pref" id="music_undefined" <?php if (!isset($connectedDriver) || ($connectedDriver->getMusicPreference() === null)) {
                            echo 'checked';
                        } ?>>
                    </div>
                </div>
            </div>
        </div>
        <?php /* endif; */ ?>

    </section>

    <!-- User's carpool -->

    <section class="carpoolsUserBlock">
        <div class="headerUserInfo">
            <h1 class="pageTitle removeMargins">Mes covoiturages</h1>
            <button class="participateButton">Proposer un covoiturage</button>
        </div>

        <div class="tabs">
            <button class="tabButton active" data-target="notStarted">En cours</button>
            <button class="tabButton" data-target="completed">Terminés</button>
        </div>

        <div id="notStarted" class="tab-content active">
            <?php include '../templates/covoiturages_not_started.php'; ?>
        </div>

        <div id="completed" class="tab-content">
            <?php include '../templates/covoiturages_completed.php'; ?>
        </div>

    </section>
</main>