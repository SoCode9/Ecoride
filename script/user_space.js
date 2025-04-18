document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("submit", function (event) {
        const form = event.target;
        if (form.classList.contains("ajax-form")) {
            event.preventDefault();
        }

    }, true);

    document.querySelectorAll(".tab-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));

            this.classList.add("active");
            document.getElementById(this.dataset.target).classList.add("active");
        });
    });

    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const addCarButton = document.getElementById("add_car_button");
    const addPrefButton = document.getElementById("add_pref_button");
    const updatePhoto = document.getElementById("edit-photo-icon");

    if (editButton && saveButton) {
        editButton.addEventListener('click', () => {
            editButton.classList.remove("active");
            saveButton.classList.add("active");

            document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                checkbox.classList.remove("radio-not-edit");
            });

            if (addCarButton) {
                addCarButton.classList.remove("hidden");
            }

            if (addPrefButton) {
                addPrefButton.classList.remove("hidden");
            }
            if (updatePhoto) {
                updatePhoto.classList.remove("hidden");
            }
            document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                checkbox.classList.remove("radio-not-edit");
            });

        });

        saveButton.addEventListener('click', () => {
            saveButton.classList.remove("active");
            editButton.classList.add("active");


            document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                checkbox.classList.add("radio-not-edit");
            });

            if (addCarButton) {
                addCarButton.classList.add("hidden");
            }

            if (addPrefButton) {
                addPrefButton.classList.add("hidden");
            }

            document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
                checkbox.classList.add("radio-not-edit");
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


    /** Display form to add a new car **/
    if (addCarButton) {
        addCarButton.addEventListener('click', () => {
            document.querySelector(".carForm").classList.remove("hidden");
        });
    }

    /** Display form to add a new preference **/
    if (addPrefButton) {
        addPrefButton.addEventListener('click', () => {
            document.querySelector(".prefForm").classList.remove("hidden");
        });
    }

    saveButton.addEventListener("click", function () {
        addCarButton.classList.add("hidden");

        addPrefButton.classList.add("hidden");

        const carForm = document.querySelector(".carForm");
        carForm.classList.add("hidden");

        const prefForm = document.querySelector(".prefForm");
        prefForm.classList.add("hidden");

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

        fetch('../back/user/update_user_profile.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded" //Indicates to the server that data is being sent via POST
            },
            body: "role_id=" + encodeURIComponent(roleId) + "&smoke_pref=" + encodeURIComponent(smokePref)
                + "&pet_pref=" + encodeURIComponent(petPref) + "&food_pref=" + encodeURIComponent(foodPref)
                + "&speak_pref=" + encodeURIComponent(speakPref) + "&music_pref=" + encodeURIComponent(musicPref) //sent to server
        },)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Profil mis à jour !");
                    location.reload();
                } else {
                    console.error("Erreur : " + data.message);
                }
            })
            .catch(error => {
                console.error("Erreur Ajax :", error);
                alert("Une erreur est survenue. Veuillez réessayer.");
            });
    });

    /** Refresh only Car's section (not full page) **/
    function refreshCarList() {
        fetch("../templates/components/cars_list.php")
            .then(response => response.text())
            .then(html => {
                let carContainer = document.getElementById("car-container");
                if (!carContainer) {
                    console.error("Erreur : car-container introuvable dans le DOM !");
                    return;
                }

                carContainer.innerHTML = html; // update car's section
            })
            .catch(error => {
                console.error("Erreur de mise à jour car-container :", error);
            });
    }

    /** Refresh only Preferences' section (not full page) **/

    function refreshPrefList() {
        fetch("../templates/components/other_pref_list.php")
            .then(response => response.text())
            .then(html => {
                let prefContainer = document.getElementById("pref-container");
                if (!prefContainer) {
                    console.error("Erreur : pref-container introuvable dans le DOM !");
                    return;
                }

                prefContainer.innerHTML = html; //update pref's section
            })
            .catch(error => {
                console.error("Erreur de mise à jour pref-container: ", error);
            })
    }

    /** Add a car **/
    const carForm = document.getElementById("car-form");

    if (carForm) {
        carForm.addEventListener("submit", function (event) {
            event.preventDefault(); // prevent page reload

            let formData = new FormData(carForm);

            fetch("../back/car/add.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("Voiture ajoutée avec succès !");
                        carForm.reset(); // Reset the form
                        refreshCarList(); // refresh the cars' list
                    } else {
                        console.error("Erreur :", data.error);
                        alert("Erreur : " + data.error);
                    }
                })
                .catch(error => {
                    console.error("Erreur AJAX :", error);
                });
        });
    }

    /**Add a pref **/
    const prefForm = document.getElementById("pref-form");
    if (prefForm) {
        prefForm.addEventListener('submit', function (event) {
            event.preventDefault(); // prevent page reload

            let formData = new FormData(prefForm);
            fetch("../back/preference/add.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())

                .then(data => {
                    if (data.success) {
                        console.log("Préférence ajoutée avec succès !");
                        prefForm.reset(); // Reset the form
                        refreshPrefList(); // refresh the preferences' list
                    } else {
                        console.error("Erreur :", data.error);
                        alert(data.error);

                    }
                })
                .catch(error => {
                    console.error("Erreur AJAX :", error);
                });

        })
    }
});