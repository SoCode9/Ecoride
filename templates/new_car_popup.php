<?php
require_once "../back/userSpaceBack.php";
?>
<div class="popup" id="popup-new-car">

    <h3>Ajouter une voiture</h3>
    <div class="filtersList">
        <form id="car-form" class="filtersList" style="gap: 10px;">

            <div class="filter">
                <label for="licence_plate">Plaque immatriculation : </label>
                <input type="text" id="licence_plate" name="licence_plate" class="textField" placeholder="AA-000-AA"
                    required>
            </div>

            <div class="filter">
                <label for="first_registration_date">Date première immatriculation : </label>
                <input type="date" id="first_registration_date" name="first_registration_date" class="textField"
                    required>
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
                <input type="radio" name="electric" value="yes" id="electric_yes" required>
                <label for="electric_yes">oui</label>

                <input type="radio" name="electric" value="no" id="electric_no" required>
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
            <div class="searchButton">
                <button type="submit" class="legendSearch" onclick="submitJS()">Enregistrer la voiture</button>
            </div>
        </form>
    </div>
    <button type="button" class="close-btn resetButton" style="justify-self:right;"
        onclick="closePopup()">Annuler</button>


</div>

<script>
    function showPopup(event) {
        document.getElementById('popup-new-car').style.display = 'block';
    }

    function closePopup(event) {
        document.getElementById('popup-new-car').style.display = 'none';
    }

    function submitJS(event) {
        const licencePlate = document.getElementById('licence_plate').value;
        const firstRegistrationDate = document.getElementById('first_registration_date').value;
        const brand = document.getElementById('brand').value;
        const model = document.getElementById('model').value;
        const electric = document.querySelector('input[name="electric"]:checked').value;
        const color = document.getElementById('color').value;
        const nbPassengers = document.getElementById('nb_passengers').value;

        fetch('/0-ECFEcoride/back/addCarBack.php', {

            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },

            body: new URLSearchParams({
                licence_plate: licencePlate,
                first_registration_date: firstRegistrationDate,
                brand: brand,
                model: model,
                electric: electric,
                color: color,
                nb_passengers: nbPassengers
            })
        })
            .then(result => result.text())
            .then(data => {
                console.log("Réponse du backend :", data);
                closePopup();
                location.reload();
            })
            .catch(error => {
                console.error("Erreur :", error);
                alert("Une erreur s'est produite lors de l'ajout de la voiture");
            });
    }
</script>