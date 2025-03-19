document.querySelectorAll(".tabButton").forEach(button => {
    button.addEventListener("click", function () {
        document.querySelectorAll(".tabButton").forEach(btn => btn.classList.remove("active"));
        document.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));

        this.classList.add("active");
        document.getElementById(this.dataset.target).classList.add("active");
    });
});

/*Enable radio button editing when I click on the Edit button*/
const editButton = document.getElementById('edit-button');
editButton.addEventListener('click', () => {
    document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
        checkbox.classList.remove("radioNotEdit");
    });
    document.getElementById("edit-button").classList.remove("active");
    document.getElementById("save-button").classList.add("active");

    const addCarButton = document.getElementById("add_car_button");
    addCarButton.classList.remove("hidden");

});

/*Disable radio button editing when I click the Save button */
const saveButton = document.getElementById('save-button');
saveButton.addEventListener('click', () => {
    document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
        checkbox.classList.add("radioNotEdit");
    });
    document.getElementById("edit-button").classList.add("active");
    document.getElementById("save-button").classList.remove("active");
});

/*if "passager" is selected-> the car and preference sections are not displayed*/
document.addEventListener("DOMContentLoaded", function () {
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
});

const addCarButton = document.getElementById('add_car_button');
addCarButton.addEventListener('click', () => {
    const carForm = document.querySelector(".carForm");
    carForm.classList.remove("hidden");
})

document.addEventListener("DOMContentLoaded", function () {
    const saveButton = document.getElementById("save-button");

    saveButton.addEventListener("click", function () {

        const addCarButton = document.getElementById("add_car_button");
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
});