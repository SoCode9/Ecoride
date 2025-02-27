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