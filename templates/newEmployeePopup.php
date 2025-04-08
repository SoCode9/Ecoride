<div class="popup" id="popup-new-employee">
    <h3>Ajouter un compte employé</h3>
    <div class="filtersList">
        <form action="../back/adminSpaceBack.php" method="POST" class="flex-column gap-12">
            <input type="hidden" name="action" value="create-employee">

            <div class="flex-row gap-8">
                <label for="pseudo_employee">Pseudo : </label>
                <input type="text" id="pseudo_employee" name="pseudo_employee" class="textField" style="flex:1;"
                    required>
            </div>
            <div class="flex-row gap-8">
                <label for="mail_employee">Email : </label>
                <input type="text" id="mail_employee" name="mail_employee" class="textField" style="flex:1;" required>
            </div>
            <div class="flex-column">
                <div class="flex-row gap-8">
                    <label for="password_employee">Mot de passe : </label>
                    <input type="password" id="password_employee" name="password_employee" class="textField"
                        style="flex:1;" required>
                </div>
                <p>
                    ✔ 8 caractères minimum <br>
                    ✔ 1 majuscule et 1 minuscule minimum<br>
                    ✔ 1 chiffre minimum<br>
                    ✔ 1 caractère spécial minimum
                </p>
            </div>
            <div class="searchButton">
                <button type="submit" class="legendSearch">Enregistrer le compte employé</button>
            </div>
        </form>
    </div>
    <button type="button" class="close-btn resetButton" style="justify-self:right;"
        onclick="closePopup()">Annuler</button>

</div>

<script>
    function showPopup(event) {
        document.getElementById('popup-new-employee').style.display = 'block';
    }

    function closePopup(event) {
        document.getElementById('popup-new-employee').style.display = 'none';
    }
</script>