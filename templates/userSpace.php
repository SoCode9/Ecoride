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
        <!--Auto section-->
        <?php if ($connectedUser->getIdRole() !== 1): ?>
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
            <!--... A COMPLETER AVEC PREFERENCES-->

        <?php endif; ?>

    </section>

    <section class="carpoolsUserBlock">

    </section>
</main>