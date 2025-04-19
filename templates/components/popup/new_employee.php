<?php
$popup_id = "popup-new-employee";
$popup_title = "Ajouter un compte employé";
ob_start(); ?>

<form action="/0-ECFEcoride/back/user/admin_space.php" method="POST" class="flex-column gap-12">
    <input type="hidden" name="action" value="create-employee">

    <div class="flex-row gap-8">
        <label for="pseudo-employee">Pseudo : </label>
        <input type="text" id="pseudo-employee" name="pseudo-employee" class="text-field" style="flex:1;" required>
    </div>
    <div class="flex-row gap-8">
        <label for="mail-employee">Email : </label>
        <input type="email" id="mail-employee" name="mail-employee" autocomplete="username" class="text-field"
            style="flex:1;" required>
    </div>
    <div class="flex-column">
        <div class="flex-row gap-8">
            <label for="password-employee">Mot de passe : </label>
            <input type="password" id="password-employee" name="password-employee" autocomplete="new-password"
                class="text-field" style="flex:1;" required>
        </div>
        <p>
            ✔ 8 caractères minimum <br>
            ✔ 1 majuscule et 1 minuscule minimum<br>
            ✔ 1 chiffre minimum<br>
            ✔ 1 caractère spécial minimum
        </p>
    </div>
    <div class="btn bg-light-green">
        <button type="submit">Enregistrer le compte employé</button>
    </div>
</form>

<?php $popup_content = ob_get_clean(); ?>

<?php include '../templates/components/popup/template.php'; ?>