const logoutButton = document.getElementById("logoutButton");
if (logoutButton) {
    logoutButton.addEventListener('click', () => {
        fetch('../back/logoutBack.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "../index/loginPageIndex.php";
                }
            })
            .catch(error => console.error('Erreur lors de la déconnexion:', error));
    });
} 