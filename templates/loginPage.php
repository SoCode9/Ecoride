<main class="blockAlign">
    <div class="loginAndCreationAccountBlock">
        <div class="loginBlock">
            <h1 class="loginTitle">Se connecter</h1>
            <form action="" method="POST" class="formConnexion">
                <input type="email" id="mail" name="mail" placeholder="Email" class="fieldConnexion" required>
                <input type="password" name="password" placeholder="Mot de passe" class="fieldConnexion" required>
                <input type="submit" value="Connexion" class="searchButton validationButton">
            </form>
        </div>
        <div class="loginBlock creationAccountBlock">
            <h1 class="loginTitle">Créer un compte</h1>
            <form action="" method="POST" class="formConnexion">
                <div>
                    <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" class="fieldConnexion" required>
                    <span style="color: #4D9856;font-size:15px; font-style:italic;">Votre pseudo sera visible par les autres utilisateurs</span>
                </div>

                <input type="email" id="mail" name="mail" placeholder="Email" class="fieldConnexion" required>
                <div>
                    <input type="password" id="password" name="password" placeholder="Mot de passe"
                        class="fieldConnexion" required>
                    <p>
                        ✔ 8 caractères minimum <br>
                        ✔ 1 majuscule et 1 minuscule minimum<br>
                        ✔ 1 nombre minimum<br>
                        ✔ 1 caractère spécial minimum
                    </p>
                </div>
                <input type="submit" value="Créer le compte" class="searchButton validationButton">
            </form>
        </div>
    </div>


</main>