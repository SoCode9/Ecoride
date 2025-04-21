document.addEventListener("DOMContentLoaded", function () {
    const currentTab = document.getElementById("current-tab-mobile");
    const currentPage = window.location.pathname.split("/").pop();

    const labels = {
        "home_page.php": "Accueil",
        "carpool_search.php": "Covoiturages",
        "login.php": "Connexion",
        "user_space.php": "Utilisateur",
        "employee_space.php": "Employé",
        "admin_space.php": "Admin"
    };

    if (labels[currentPage]) {
        currentTab.textContent = labels[currentPage];
    }
});
var sidenav = document.getElementById("my-sidenav");
var openBtn = document.getElementById("open-btn");
var closeBtn = document.getElementById("close-btn");

openBtn.onclick = openNav;
closeBtn.onclick = closeNav;

/* Set the width of the side navigation to 250px */
function openNav() {
    sidenav.classList.add("active");
}

/* Set the width of the side navigation to 0 */
function closeNav() {
    sidenav.classList.remove("active");
}