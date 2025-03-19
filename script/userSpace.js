document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("submit", function (event) {
        console.log("🔴 Un autre gestionnaire de soumission existe !");
        event.preventDefault();
    }, true);
    //gestion des onglets
    console.log("JavaScript chargé !");

    function reattachEventListeners() {

        document.querySelectorAll(".tabButton").forEach(button => {
            button.addEventListener("click", function () {
                document.querySelectorAll(".tabButton").forEach(btn => btn.classList.remove("active"));
                document.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));

                this.classList.add("active");
                document.getElementById(this.dataset.target).classList.add("active");
            });
        });

        const editButton = document.getElementById('edit-button');
        const saveButton = document.getElementById('save-button');
        const addCarButton = document.getElementById("add_car_button");

        if (editButton && saveButton) {
            editButton.addEventListener('click', () => {
                console.log("Bouton Modifier cliqué, activation du mode édition.");
                editButton.classList.remove("active"); //TEST AVEC .add("hidden")
                saveButton.classList.add("active"); // TEST AVEC .remove("hidden") S'assurer que "Sauvegarder" apparaît

                document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                    checkbox.classList.remove("radioNotEdit");
                });

                if (addCarButton) {
                    addCarButton.classList.remove("hidden");
                }

                document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                    checkbox.classList.remove("radioNotEdit");
                });

            });

            saveButton.addEventListener('click', () => {
                console.log("Bouton Sauvegarder cliqué, retour au mode normal.");
                saveButton.classList.remove("active"); //TEST avec  .add("hidden")
                editButton.classList.add("active"); //TEST AVEC .remove("hidden") Réafficher "Modifier"


                document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                    checkbox.classList.add("radioNotEdit");
                });

                if (addCarButton) {
                    addCarButton.classList.add("hidden");
                }

                document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                    checkbox.classList.add("radioNotEdit");
                });

            });
        }





        /*if "passager" is selected-> the car and preference sections are not displayed*/
        const roleRadios = document.querySelectorAll('input[name="user_role"]');
        const carSection = document.querySelector(".scrollable-container");

        function toggleCarSection() {
            let selectedRole = document.querySelector('input[name="user_role"]:checked').id;

            if (selectedRole === "role_passenger") {
                carSection.classList.add("hidden"); // Hide the section
            } else {
                carSection.classList.remove("hidden"); // Display the section
            }
        }

        // Execute on loading to apply the right condition on departure
        toggleCarSection();

        // Check the condition when I change the selection
        roleRadios.forEach(radio => {
            radio.addEventListener("change", toggleCarSection);
        });


        /** ✅ Afficher le formulaire d'ajout de voiture **/
        if (addCarButton) {
            addCarButton.addEventListener('click', () => {
                console.log("Ajout de voiture activé !");
                document.querySelector(".carForm").classList.remove("hidden");
            });
        }
        /* addCarButton.addEventListener('click', () => {
            const carForm = document.querySelector(".carForm");
            carForm.classList.remove("hidden");
        }) */


        saveButton.addEventListener("click", function () {
            console.log("Envoi des préférences utilisateur...");

            addCarButton.classList.add("hidden");

            const carForm = document.querySelector(".carForm");
            carForm.classList.add("hidden");

            let selectedRole = document.querySelector('input[name="user_role"]:checked').id;
            let selectedSmokePref = document.querySelector('input[name = "smoke_pref"]:checked').id;
            let selectedPetPref = document.querySelector('input[name="pet_pref"]:checked').id;
            let selectedFoodPref = document.querySelector('input[name="food_pref"]:checked').id;
            let selectedSpeakPref = document.querySelector('input[name="speak_pref"]:checked').id;
            let selectedMusicPref = document.querySelector('input[name="music_pref"]:checked').id;

            let roleId;
            if (selectedRole === "role_passenger") roleId = 1;
            if (selectedRole === "role_driver") roleId = 2;
            if (selectedRole === "role_both") roleId = 3;

            let smokePref;
            if (selectedSmokePref === "smoke_yes") smokePref = 1;
            if (selectedSmokePref === "smoke_no") smokePref = 0;
            if (selectedSmokePref === "smoke_undefined") smokePref = "NULL";

            let petPref;
            if (selectedPetPref === "pet_yes") petPref = 1;
            if (selectedPetPref === "pet_no") petPref = 0;
            if (selectedPetPref === "pet_undefined") petPref = "NULL";

            let foodPref;
            if (selectedFoodPref === "food_yes") foodPref = 1;
            if (selectedFoodPref === "food_no") foodPref = 0;
            if (selectedFoodPref === "food_undefined") foodPref = "NULL";

            let speakPref;
            if (selectedSpeakPref === "speak_yes") speakPref = 1;
            if (selectedSpeakPref === "speak_no") speakPref = 0;
            if (selectedSpeakPref === "speak_undefined") speakPref = "NULL";

            let musicPref;
            if (selectedMusicPref === "music_yes") musicPref = 1;
            if (selectedMusicPref === "music_no") musicPref = 0;
            if (selectedMusicPref === "music_undefined") musicPref = "NULL";

            fetch('../back/updateUserRoleBack.php', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded" //Indique au serveur que des données sont envoyées en POST
                },
                body: "role_id=" + encodeURIComponent(roleId) + "&smoke_pref=" + encodeURIComponent(smokePref)
                    + "&pet_pref=" + encodeURIComponent(petPref) + "&food_pref=" + encodeURIComponent(foodPref)
                    + "&speak_pref=" + encodeURIComponent(speakPref) + "&music_pref=" + encodeURIComponent(musicPref) //sent to server
            },)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Profil mis à jour !"); // A ENLEVER !
                    } else {
                        alert("Erreur : " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Erreur Ajax :", error);
                    alert("Une erreur est survenue. Veuillez réessayer.");
                });
        });

        /** ✅ Afficher le formulaire d'ajout de voiture **/
        if (addCarButton) {
            addCarButton.addEventListener("click", () => {
                document.querySelector(".carForm").classList.remove("hidden");
            });
        }

    }

    /** ✅ Rafraîchir la section "Voitures" sans recharger la page **/
    function refreshCarList() {
        console.log("🔄 Demande de mise à jour de la liste des voitures...");


        fetch("../templates/load_cars.php")
            .then(response => response.text())
            .then(html => {
                console.log("✅ Réponse reçue, mise à jour en cours...");
                console.log("📥 Contenu renvoyé par load_cars.php :", html); // ✅ Affiche la réponse du serveur

                let carContainer = document.getElementById("car-container"); // ✅ Ajout de la déclaration
                if (!carContainer) {
                    console.error("❌ Erreur : car-container introuvable dans le DOM !");
                    return;
                }

                carContainer.innerHTML = html; // 🔥 Mise à jour de la section voitures
                reattachEventListeners(); // ✅ Réattacher les événements après mise à jour
            })
            .catch(error => {
                console.error("❌ Erreur de mise à jour :", error);
            });
    }
    /** ✅ Ajouter une voiture via AJAX **/
    const carForm = document.getElementById("car-form");

    if (carForm) {
        carForm.addEventListener("submit", function (event) {
            console.log("🛑 Soumission du formulaire interceptée !");

            event.preventDefault(); // 🚀 Empêcher le rechargement de la page

            let formData = new FormData(carForm);

            fetch("../back/addCarBack.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text()) // 🔥 On récupère la réponse brute
                .then(text => {
                    console.log("📥 Réponse brute du serveur :", text); // ✅ Affiche la réponse dans la console
                    return JSON.parse(text); // ✅ Convertir en JSON
                })
                .then(data => {
                    if (data.success) {
                        console.log("🚗 Voiture ajoutée avec succès !");
                        alert("Voiture ajoutée !");
                        carForm.reset(); // ✅ Réinitialiser le formulaire
                        refreshCarList(); // ✅ Rafraîchir la liste des voitures
                    } else {
                        console.error("❌ Erreur :", data.error);
                        alert("Erreur : " + data.error);
                    }
                })
                .catch(error => {
                    console.error("❌ Erreur AJAX :", error);
                });
        });
    }
    /** ✅ Appel initial pour s'assurer que tout est bien attaché **/
    reattachEventListeners();
});