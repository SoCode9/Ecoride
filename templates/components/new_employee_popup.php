<div class="popup" id="popup-new-employee">
    <h3>Ajouter un compte employé</h3>
    <div class="filtersList">
        <form action="../back/user/admin_space.php" method="POST" class="flex-column gap-12">
            <input type="hidden" name="action" value="create-employee">

            <div class="flex-row gap-8">
                <label for="pseudo-employee">Pseudo : </label>
                <input type="text" id="pseudo-employee" name="pseudo-employee" class="textField" style="flex:1;"
                    required>
            </div>
            <div class="flex-row gap-8">
                <label for="mail-employee">Email : </label>
                <input type="text" id="mail-employee" name="mail-employee" class="textField" style="flex:1;" required>
            </div>
            <div class="flex-column">
                <div class="flex-row gap-8">
                    <label for="password-employee">Mot de passe : </label>
                    <input type="password" id="password-employee" name="password-employee" class="textField"
                        style="flex:1;" required>
                </div>
                <p>
                    ✔ 8 caractères minimum <br>
                    ✔ 1 majuscule et 1 minuscule minimum<br>
                    ✔ 1 chiffre minimum<br>
                    ✔ 1 caractère spécial minimum
                </p>
            </div>
            <div class="btn bg-light-green">
                <button type="submit" class="legendSearch">Enregistrer le compte employé</button>
            </div>
        </form>
    </div>
    <button type="button" class="col-back-grey-btn btn" style="justify-self:right;"
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