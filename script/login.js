const logoutButton = document.getElementById("logoutButton");
if (logoutButton) {
    logoutButton.addEventListener('click', () => {
        fetch('../back/user/logout.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = BASE_URL + "/controllers /login.php";
                }
            })
            .catch(error => console.error('Erreur lors de la déconnexion:', error));
    });
} 