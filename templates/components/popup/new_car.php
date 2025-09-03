<?php
require_once "../back/user/user_space.php";


$popup_id = "popup-new-car";
$popup_title = "Ajouter une voiture";
ob_start(); ?>
<form id="car-form-id" class="block-column-g20" style="gap: 10px;">

    <div class="flex-row flex-column-ss">
        <label for="licence_plate">Plaque immatriculation : </label>
        <input type="text" id="licence-plate" name="licence_plate" class="text-field" placeholder="AA-000-AA">
    </div>

    <div class="flex-row flex-column-ss">
        <label for="first_registration_date">Date première immatriculation : </label>
        <input type="date" id="first-registration-date" name="first_registration_date" class="text-field">
    </div>
    <div class="flex-row flex-column-ss">
        <label for="brand">Marque : </label>
        <select id="brand" class="text-field" name="brand">
            <option value="">Sélectionner</option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?= htmlspecialchars($brand['id']); ?>">
                    <?= htmlspecialchars($brand['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex-row flex-column-ss">
        <label for="model">Modèle : </label>
        <input type="text" id="model" name="model" class="text-field">
    </div>
    <div class="flex-row">
        <label for="electric">Electrique : </label>
        <input type="radio" name="electric" value="yes" id="electric-yes">
        <label for="electric_yes">oui</label>

        <input type="radio" name="electric" value="no" id="electric-no">
        <label for="electric_no">non</label>

    </div>
    <div class="flex-row flex-column-ss">
        <label for="color">Couleur : </label>
        <input type="text" id="color" name="color" class="text-field">
    </div>
    <div class="flex-row">
        <label for="nb_passengers">Nombre de passagers possible : </label>
        <input type="number" id="nb-passengers" name="nb_passengers" class="text-field"
            style="width: 40px;">
    </div>
    <div class="btn bg-light-green">
        <button type="submit">Enregistrer la voiture</button>
    </div>
</form>

<?php $popup_content = ob_get_clean(); ?>
<script>
    function showPopupCar(event) {
        event.preventDefault();
        document.getElementById('popup-new-car').style.display = 'block';

        const licencePlate = document.getElementById('licence-plate');
        const firstRegistrationDate = document.getElementById('first-registration-date');
        const brand = document.getElementById('brand');
        const model = document.getElementById('model');
        const electric = document.getElementById('electric-yes');
        const color = document.getElementById('color');
        const nbPassengers = document.getElementById('nb-passengers');

        licencePlate.setAttribute('required', '');
        firstRegistrationDate.setAttribute('required', '');
        brand.setAttribute('required', '');
        model.setAttribute('required', '');
        electric.setAttribute('required', '');
        color.setAttribute('required', '');
        nbPassengers.setAttribute('required', '');
    }

    function closePopupCar() {
        document.getElementById('popup-new-car').style.display = 'none';

        const licencePlate = document.getElementById('licence-plate');
        const firstRegistrationDate = document.getElementById('first-registration-date');
        const brand = document.getElementById('brand');
        const model = document.getElementById('model');
        const electric = document.getElementById('electric-yes');
        const color = document.getElementById('color');
        const nbPassengers = document.getElementById('nb-passengers');

        licencePlate.removeAttribute('required', '');
        firstRegistrationDate.removeAttribute('required', '');
        brand.removeAttribute('required', '');
        model.removeAttribute('required', '');
        electric.removeAttribute('required', '');
        color.removeAttribute('required', '');
        nbPassengers.removeAttribute('required', '');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const carForm = document.getElementById('car-form-id');
        if (carForm) {
            carForm.addEventListener('submit', function(event) {
                event.preventDefault();
                submitJS();
            });
        }
    });

    /** Refresh only Car's section (not full page) **/
    function refreshCarField() {
        fetch("../templates/components/lists/car_select.php")
            .then(response => response.text())
            .then(html => {
                let carContainer = document.getElementById("car-field");
                if (!carContainer) {
                    console.error("Erreur : car-field introuvable dans le DOM !");
                    return;
                }

                carContainer.innerHTML = html;
            })
            .catch(error => {
                console.error("Erreur de mise à jour car-field :", error);
            });
    }

    function submitJS() {
        const licencePlate = document.getElementById('licence-plate').value;
        const firstRegistrationDate = document.getElementById('first-registration-date').value;
        const brand = document.getElementById('brand').value;
        const model = document.getElementById('model').value;
        const electricInput = document.querySelector('input[name="electric"]:checked');
        const electric = electricInput ? electricInput.value : '';
        const color = document.getElementById('color').value;
        const nbPassengers = document.getElementById('nb-passengers').value;

        fetch('../back/car/add.php', {

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
                closePopupCar();
                refreshCarField();
            })
            .catch(error => {
                console.error("Erreur :", error);
                alert("Une erreur s'est produite lors de l'ajout de la voiture");
            });
    }
</script>

<?php include __DIR__ . '/../../../templates/components/popup/template.php'; ?>