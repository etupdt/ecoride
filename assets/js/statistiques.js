
const covoiturages = document.querySelector('#covoiturages');
const credits = document.querySelector('#credits');

if (covoiturages !== null) {

    displayGraphiques();

}

async function displayGraphiques() {

    try {
    
        const reponse = await fetch(`/admin/covoiturages`, {
    
            method: "GET",

        });

        const resultat = await reponse.json();

        new Chart(covoiturages, {

            type: 'bar',
            data: {
                labels: resultat.labels1,
                datasets: [
                    {
                        label: 'Covoiturages par jour',
                        data: resultat.data1,
                        borderWidth: 1
                    }
            ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }

        });

        new Chart(credits, {

            type: 'bar',
            data: {
                labels: resultat.labels2,
                datasets: [
                    {
                        label: 'Crédits par jour',
                        data: resultat.data2,
                        borderWidth: 1
                    }
            ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }

        });

    } catch (erreur) {
        console.error("Erreur :", erreur);
    }

}
