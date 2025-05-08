document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('participate').addEventListener('click', function () {
        let travelId = this.getAttribute("data-id"); // Retrieve travel id

        // Sent AJAX query to server to check available seats
        fetch("../back/reservation/check_participation.php", {
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
                        return;
                    }
                    // Check if user has  credit enough
                    if (data.userCredits < data.travelPrice) {
                        alert("Vous n'avez pas assez de crédits pour réserver ce covoiturage.");
                        return; 
                    }

                    // double confirmation
                    let confirmParticipation = confirm("Souhaitez-vous vraiment participer à ce covoiturage ?")
                    if (confirmParticipation) {
                        updateParticipation(travelId);
                    }
                }

                // If the user is not logged in, suggest he/she log in.
                if (data.message && typeof data.message === "string" && data.message.includes("Utilisateur non connecte")) {

                    let confirmLogin = confirm("Vous devez être connecté pour réserver. Cliquer sur \"OK\" pour créer un compte.");
                    if (confirmLogin) {
                        window.location.href = BASE_URL + "/controllers/login.php";
                    }
                }

                if (data.message && data.message.includes("Utilisateur déjà inscrit à ce covoiturage")) {
                    alert("Vous êtes déjà inscrit à ce covoiturage.")
                }

                if (data.message && data.message.includes("Le covoiturage est soit en cours, soit annulé, soit terminé")) {
                    alert("Impossible de participer au covoiturage.")
                }
            })
            .catch(error => {
                console.error("Erreur AJAX :", error);
                alert("Une erreur est survenue. Veuillez réessayer plus tard.");
            });
    });

    function updateParticipation(travelId) {
        fetch("../back/reservation/update_participation.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "travel_id=" + travelId // sent travel id to server
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Votre participation a été confirmée !");
                }
            })
    }
});