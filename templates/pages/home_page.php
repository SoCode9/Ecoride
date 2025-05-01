<main>

    <!-- Search section -->
    <section class="search"
        style=" background: url('<?= BASE_URL ?> /icons/Accueil_transp.jpg') center/cover no-repeat;">
        <h1>
            EcoRide, la plateforme qui révolutionne vos déplacements<br>
            tout en respectant l'environnement.
        </h1>

        <div class="flex-column gap-24 block-light-grey">
            <h2 class="text-green text-bold">Rechercher un covoiturage</h2>
            <form class="block-search" action= "<?= BASE_URL ?> /controllers /carpool_search.php" method="POST">
                <input type="hidden" name="action" value="search">

                <div class="flex-row gap-4 search-field">
                    <img class="img-width-20" src="<?= BASE_URL ?> /icons/Localisation(2).png" alt="lieu de départ">
                    <input type="text" id="departure-city-search" name="departure-city-search"
                        class="font-size-small text-breakable  " placeholder="De"
                        value="<?= isset($_SESSION['departure-city-search']) ? htmlspecialchars($_SESSION['departure-city-search']) : '' ?>"
                        required>
                    <div id="departure-suggestions" class="suggestions-list"></div>
                </div>

                <span class="flex-row">→</span>

                <div class="flex-row gap-4 search-field">
                    <img class="img-width-20" src="<?= BASE_URL ?> /icons/Localisation(2).png" alt="">
                    <input type="text" id="arrival-city-search" name="arrival-city-search"
                        class="font-size-small text-breakable  " placeholder="À"
                        value="<?= isset($_SESSION['arrival-city-search']) ? htmlspecialchars($_SESSION['arrival-city-search']) : '' ?>"
                        required>
                    <div id="arrival-suggestions" class="suggestions-list"></div>
                </div>

                <div class="flex-row gap-4 search-field">
                    <img class="img-pointer" src="<?= BASE_URL ?> /icons/Calendrier2.png" alt="Calendrier">
                    <input type="date" id="departure-date-search" name="departure-date-search"
                        class="date-field font-size-small  " style="width:110px;"
                        value="<?= isset($_SESSION['departure-date-search']) ? htmlspecialchars($_SESSION['departure-date-search']) : '' ?>"
                        required>
                </div>

                <div class="btn bg-light-green">
                    <img class="img-width-20" src="<?= BASE_URL ?> /icons/LoupeRecherche.png" alt="">
                    <input type="submit" value="Rechercher">
                </div>
            </form>
        </div>
    </section>

    <!--  About section -->
    <section class="about">
        <div class="text">
            <h2>QUI SOMMES-NOUS ?</h2>
            <p>
                Lancée en 2025 en France, EcoRide est une startup innovante dédiée à la promotion du covoiturage
                écoresponsable.<br><br>
                Notre objectif est le vôtre : réduire l'empreinte carbone des trajets quotidiens tout en offrant une
                solution pratique et économique pour tous ceux qui souhaitent voyager de manière plus verte.<br><br>
                EcoRide se veut la référence du covoiturage automobile pour les voyageurs soucieux de l'environnement.
                Grâce à notre application web intuitive, nous mettons en relation des conducteurs et passagers
                partageant des trajets similaires, afin de réduire le nombre de voitures sur les routes et de promouvoir
                une mobilité plus durable.<br><br>
                Rejoignez-nous et faites un geste pour la planète tout en optimisant vos déplacements.<br><br>
                EcoRide, c'est l'avenir du transport durable.
            </p>
        </div>
        <div class="image">
            <img src="<?= BASE_URL ?> /icons/Accueil_GPT.png" alt="Image de covoiturage EcoRide">
        </div>
    </section>

</main>