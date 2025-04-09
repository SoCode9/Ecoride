let chart = document.querySelector("#carpoolsPerDayChart");

new Chart(chart, {
    type: "bar",
    data: {
        labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
        datasets: [{
            label: 'Nombre de covoiturages par jour',
            data: [3, 1, 4, 12, 0, 20, 24], // CHIFFRES A AUTOMATISER
            backgroundColor:'#68C990'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: "Nombre de covoiturages"
                }
            },
            x: {
                title: {
                    display: true,
                    text: "Semaine en cours"
                }
            }
        },
        /* plugins: {
            title: {
                display: true,
                text: "Evolution des covoiturages"
            },
            legend: {
                display: true,
                position: 'top'
            }
        } */
    }
})