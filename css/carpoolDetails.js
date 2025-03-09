document.addEventListener("DOMContentLoaded", function () {
    document.querySelector('.participateButton').addEventListener('click', function () {
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
                        return;// On stoppe ici 
                    }
                    // Check if user has  credit enough
                    if (data.userCredits < data.travelPrice) {
                        alert("Vous n'avez pas assez de crédits pour réserver ce covoiturage.");
                        return; // On stoppe ici
                    }
                    // #### si tout est bon -> ajouter la double confirmation à la place ! 
                    alert("Il reste " + data.availableSeats + " places disponibles !");
                    alert("Crédits OK, vous pouvez réserver !");


                } 
                
                // If the user is not logged in, suggest he/she log in.
                if (data.message.includes("Utilisateur non connecte")) {
                    console.log("User non connecté, affichage du confirm()");

                    let confirmLogin = confirm("Vous devez être connecté pour réserver. Cliquer sur \"OK\" pour créer un compte.");
                    if (confirmLogin) {
                        window.location.href = "../index/carpoolSearchIndex.php"; // CHANGER LA REDIRECTION VERS PAGE CONNEXION
                    }
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => {
                console.error("Erreur AJAX :", error);
                alert("Une erreur est survenue. Veuillez réessayer plus tard.");
            });
    });
});