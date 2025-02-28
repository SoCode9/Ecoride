//const Orange = #F2C674; ??



//the calender icon opens the calender

document.querySelector('.imgFilterDate').addEventListener('click', () => {
    const dateInput = document.querySelector('#departure-date-search'); // Vérifie si le navigateur supporte showPicker, sinon fallback sur focus
    if (dateInput.showPicker) {
        dateInput.showPicker(); // Force l'ouverture du calendrier
    } else {
        dateInput.focus(); // Fallback pour les anciens navigateurs
    }
});

//API to search locations in France

// API pour rechercher des villes en France
async function searchLocation(nom) {
    const response = await fetch(`https://geo.api.gouv.fr/communes?nom=${nom}&fields=nom,departement&boost=population&limit=5`);
    if (!response.ok) {
        console.error("Erreur lors de la récupération des données");
        return [];
    }
    return await response.json();
}

// Fonction pour mettre à jour la liste des suggestions
function updateSuggestions(inputField, suggestionsContainer, data) {
    suggestionsContainer.innerHTML = ""; // Efface les anciennes suggestions

    if (data.length === 0) {
        suggestionsContainer.style.display = "none"; // Cache la liste si aucune suggestion
        return;
    }

    suggestionsContainer.style.display = "block"; // Affiche la liste

    data.forEach(ville => {
        let villeFormattee = `${ville.nom} (${ville.departement.nom})`; // Format "Ville (Département)"

        let div = document.createElement("div");
        div.classList.add("suggestion-item");
        div.textContent = villeFormattee;

        // Remplir le champ avec la ville sélectionnée et cacher la liste
        div.addEventListener("click", function() {
            inputField.value = villeFormattee; // Met le format correct dans l'input
            suggestionsContainer.innerHTML = ""; // Cache la liste après sélection
            suggestionsContainer.style.display = "none";
        });

        suggestionsContainer.appendChild(div);
    });
}

// Fonction pour ajouter l'autocomplétion à un champ spécifique
function setupAutocomplete(inputId, suggestionsId) {
    const inputField = document.getElementById(inputId);
    const suggestionsContainer = document.getElementById(suggestionsId);

    // Affichage des suggestions en fonction de la saisie
    inputField.addEventListener("input", async function() {
        let query = this.value.trim();
        if (query.length > 2) {
            let results = await searchLocation(query);
            updateSuggestions(inputField, suggestionsContainer, results);
        } else {
            suggestionsContainer.innerHTML = "";
            suggestionsContainer.style.display = "none";
        }
    });

    // Cacher la liste si on clique en dehors du champ
    document.addEventListener("click", function(event) {
        if (!inputField.contains(event.target) && !suggestionsContainer.contains(event.target)) {
            suggestionsContainer.style.display = "none";
        }
    });

    // Réafficher la liste si on clique dans le champ (et qu'il contient déjà une valeur)
    inputField.addEventListener("focus", async function() {
        let query = this.value.trim();
        if (query.length > 2) {
            let results = await searchLocation(query);
            updateSuggestions(inputField, suggestionsContainer, results);
            suggestionsContainer.style.display = "block";
        }
    });
}

// Initialisation de l'autocomplétion pour les deux champs
setupAutocomplete("departure-city-search", "departure-suggestions");
setupAutocomplete("arrival-city-search", "arrival-suggestions");