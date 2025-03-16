document.querySelectorAll(".tabButton").forEach(button => {
    button.addEventListener("click", function () {
        document.querySelectorAll(".tabButton").forEach(btn => btn.classList.remove("active"));
        document.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));

        this.classList.add("active");
        document.getElementById(this.dataset.target).classList.add("active");
    });
});

const editButton = document.getElementById('edit-button');
editButton.addEventListener('click', () => {
    document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
        checkbox.classList.remove("radioNotEdit");
    });
    document.getElementById("edit-button").classList.remove("active");
    document.getElementById("save-button").classList.add("active");
});

const saveButton = document.getElementById('save-button');
saveButton.addEventListener('click', () => {
    document.querySelectorAll('input[type="radio"]').forEach(checkbox => {
        checkbox.classList.add("radioNotEdit");
    });
    document.getElementById("edit-button").classList.add("active");
    document.getElementById("save-button").classList.remove("active");
});

/*if "passager" is selected-> the car and preference sections are displayed*/
document.addEventListener("DOMContentLoaded", function () {
    const roleRadios = document.querySelectorAll('input[name="user_role"]');
    const carSection = document.querySelector(".scrollable-container");

    function toggleCarSection() {
        let selectedRole = document.querySelector('input[name="user_role"]:checked').id;

        if (selectedRole === "role_passager") {
            carSection.style.display = "none"; // Hide the section
        } else {
            carSection.style.display = "block"; // Display the section
        }
    }

    // Execute on loading to apply the right condition on departure
    toggleCarSection();

    // Check the condition when I change the selection
    roleRadios.forEach(radio => {
        radio.addEventListener("change", toggleCarSection);
    });
});
