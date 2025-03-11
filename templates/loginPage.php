<main class="blockAlign">
    <div class="loginAndCreationAccountBlock">
        <div class="loginBlock">
            <h1 class="loginTitle">Se connecter</h1>
            <form action="" method="POST" class="formConnexion">
                <input type="text" name="pseudo" placeholder="Pseudo" class="fieldConnexion" required>
                <input type="password" name="password" placeholder="Mot de passe" class="fieldConnexion" required>
                <input type="submit" value="Connexion" class="searchButton validationButton">
            </form>
            <!--  <span>Mot de passe oublié ?</span> JE PENSE A ENLEVER-->
        </div>
        <div class="loginBlock creationAccountBlock">
            <h1 class="loginTitle">Créer un compte</h1>
            <form action="" method="POST" class="formConnexion">
                <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" class="fieldConnexion" required>
                <input type="email" id="mail" name="mail" placeholder="Email" class="fieldConnexion" required>
                <input type="password" id="password" name="password" placeholder="Mot de passe" class="fieldConnexion"
                    required>
                <div class="driverQuestionBlock">
                    <label for="chauffeur" class="driverQuestionLegend">Je propose mes services de chauffeur : </label>
                    <input type="checkbox" id="chauffeur" name="chauffeur" class="driverQuestionBox">
                </div>
                <input type="submit" value="Créer le compte" class="searchButton validationButton">
            </form>
        </div>
    </div>


</main>