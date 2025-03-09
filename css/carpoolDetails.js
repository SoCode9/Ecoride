document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('.participateButton').addEventListener('click', function() {
        let travelId = this.getAttribute("data-id"); // Retrieve travel id

        // Sent AJAX query to server to check available seats
        fetch("../back/check_seats.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "travel_id=" + travelId // sent travel id to server
        })
        .then(response => response.json()) // We expect a JSON response
        .then(data => {
            if (data.success) {
                if (data.availableSeats === 0) {
                    alert("Désolé, il n'y a plus de places disponibles.");
                }
            } else {
                alert("Erreur lors de la vérification des places.");
            }
        })
        .catch(error => console.error("Erreur :", error));
    });
});