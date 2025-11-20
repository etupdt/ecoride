
// const payer = document.querySelector('#payer')

// if (payer !== null) {

//     payer.addEventListener('click', e => postPayer())

// }

// async function postPayer() {

//     const covoiturage_id = document.querySelector('#covoiturage_id').innerHTML
//     const action = document.querySelector('#bouton_action')

//     try {
    
//         const reponse = await fetch(`/covoiturage/p/${covoiturage_id}`, {
    
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/json",
//             },
//             body: JSON.stringify({'statut': action.innerText}),

//         });

//         const resultat = await reponse.json();
//         console.log("Réussite :", resultat);
//         switch (action.innerText) {
//             case 'démarrer' : {
//                 action.innerText = 'arrivé à destination'
//                 break;
//             }
//             case 'arrivé à destination' : {
//                 action.setAttribute('display', 'none')
//                 break;
//             }
//             case 'participer' : {
//                 action.setAttribute('display', 'none')
//                 break;
//             }
//         }

//     } catch (erreur) {
//         console.error("Erreur :", erreur);
//     }

// }
