/*
* Welcome to your app's main JavaScript file!
*
* This file will be included onto the page via the importmap() Twig function,
* which should already be in your base.html.twig.
*/
import './styles/app.scss';
import './styles/energies.scss';
import './styles/marques.scss';
import './styles/voitures.scss';
import './styles/covoiturages.scss';

// import './js/photos.js';


// import './bootstrap.js';

// import 'bootstrap';
// import bsCustomFileInput from 'bs-custom-file-input';

// bsCustomFileInput.init();


// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

const $ = require('jquery');
global.$ = global.jQuery = $;
require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

document.addEventListener('turbo:load', function (e) {
    // this enables bootstrap tooltips globally
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl)
    });
});
