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
            <button class="seeDetailTrajet">Modifier le profil</button>
        </div>
        <div class="mailAndCredits">
            <span><?php echo htmlspecialchars($connectedUser->getMail()) ?></span>
            <span><?php echo htmlspecialchars($connectedUser->getCredit()) ?> crédits</span>
        </div>
        <div class="subTitleAndContent">
            <h2 class="subTitleGreen">Type d'utilisateur</h2>
            <div class="treeRadioButton">
                <div class="filter">
                    <label for="">passager</label>
                    <input type="checkbox" <?php if ($connectedUser->getIdRole() === 1) {
                        echo 'checked';
                    } ?>   class="checkboxNotEdit">
                </div>
                <div class="filter">
                    <label for="">chauffeur</label>
                    <input type="checkbox" <?php if ($connectedUser->getIdRole() === 2) {
                        echo 'checked';
                    } ?>   class="checkboxNotEdit">
                </div>
                <div class="filter">
                    <label for="">les deux</label>
                    <input type="checkbox" <?php if ($connectedUser->getIdRole() === 3) {
                        echo 'checked';
                    } ?>
                        class="checkboxNotEdit">
                </div>
            </div>
        </div>
        <!--Cars section-->
        <?php if ($connectedUser->getIdRole() !== 1): ?>
            <div class="scrollable-container subTitleAndContent">

                <div class="subTitleAndContent greyBlock">
                    <h2 class="subTitleGreen">Voitures</h2>
                    <?php
                    $totalCars = count($cars);
                    $index = 0;
                    foreach ($cars as $car):
                        $index++;
                        ?>

                        <span>Plaque immatriculation : <?= htmlspecialchars($car['car_licence_plate']) ?></span>
                        <span>Date première immatriculation :
                            <?= formatDate(htmlspecialchars($car['car_first_registration_date'])) ?></span>
                        <span>Marque : <?= htmlspecialchars($car['name']) ?></span>
                        <span>Modèle : <?= htmlspecialchars($car['car_model']) ?></span>
                        <span>Electrique ? : <?php
                        $electric = (htmlspecialchars($car['car_electric']) === 1) ? 'Oui' : 'Non';
                        echo $electric;
                        ?>
                        </span>
                        <span>Couleur : <?= htmlspecialchars($car['car_color']) ?></span>
                        <span>Nombre de passagers possible : <?= htmlspecialchars($car['car_seats_offered']) ?></span>
                        <?php if ($index !== $totalCars):
                            echo '<hr>' ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!--preferences section-->
                <div class="subTitleAndContent greyBlock">
                    <h2 class="subTitleGreen">Préférences en tant que chauffeur</h2>

                    <span>Voyager avec des fumeurs ne me dérange pas</span>
                    <div class="treeRadioButton">
                        <div class="filter">
                            <label for="">Oui</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getSmokerPreference() === true) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Non</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getSmokerPreference() === false) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Pas de préférence</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getSmokerPreference() === null) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>J'aime la compagnie des animaux</span>
                    <div class="treeRadioButton">
                        <div class="filter">
                            <label for="">Oui</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getPetPreference() === true) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Non</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getPetPreference() === false) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Pas de préférence</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getPetPreference() === null) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>La nourriture est autorisée dans la voiture</span>
                    <div class="treeRadioButton">
                        <div class="filter">
                            <label for="">Oui</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getFoodPreference() === true) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Non</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getFoodPreference() === false) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Pas de préférence</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getFoodPreference() === null) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>Je discute volontiers avec mes passagers</span>
                    <div class="treeRadioButton">
                        <div class="filter">
                            <label for="">Oui</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getSpeakerPreference() === true) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Non</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getSpeakerPreference() === false) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Pas de préférence</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getSpeakerPreference() === null) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                    <hr>
                    <span>J'aime conduire en écoutant de la musique</span>
                    <div class="treeRadioButton">
                        <div class="filter">
                            <label for="">Oui</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getMusicPreference() === true) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Non</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getMusicPreference() === false) {
                                echo 'checked';
                            } ?>>
                        </div>
                        <div class="filter">
                            <label for="">Pas de préférence</label>
                            <input type="checkbox" class="checkboxNotEdit" <?php if ($connectedDriver->getMusicPreference() === null) {
                                echo 'checked';
                            } ?>>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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