//chart carpools per day
const carpoolsPerDayChart = document.querySelector("#carpoolsPerDayChart");
fetch("../back/carpool/chart_per_day.php")
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        if (carpoolsPerDayChart) {
            createChart(carpoolsPerDayChart, data, 'bar', 'Nb de covoiturages sur les prochains jours', 'Nb de covoiturages', 'Dix prochains jours', 'travelDate', 'nbCarpool')
        } else {
            console.warn("Élément #carpoolsPerDayChart introuvable.");
        }
    });

//chart credits earned by the platform
const creditsEarnedByPlatform = document.querySelector("#creditsEarnedByPlatform");
fetch("../back/carpool/chart_credits_earned.php")
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        if (creditsEarnedByPlatform) {
            createChart(creditsEarnedByPlatform, data, 'bar', 'Nb de crédits gagnés dans les derniers jours', 'Nb de crédits gagnés', 'Dix derniers jours', 'validationCarpoolDate', 'carpoolsValidated')
        } else {
            console.warn("Élément #creditsEarnedByPlatform introuvable.");
        }
    });

function createChart(chartElement, chartData, type, label, yTitle, xTitle, labelKey, dataKey) {


    new Chart(chartElement, {
        type: type,
        data: {
            labels: chartData.map(row => row[labelKey]),
            datasets: [{
                label: label,
                data: chartData.map(row => row[dataKey]),
                backgroundColor: '#68C990'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom' 
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: yTitle
                    },
                    ticks: {
                        stepSize: 1, // Integer intervals
                        precision: 0 // Deletes decimals
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: xTitle
                    }
                }
            },
        }
    })
}