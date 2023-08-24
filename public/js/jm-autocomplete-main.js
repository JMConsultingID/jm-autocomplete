(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
const accessToken = 'pk.eyJ1Ijoia2V0dXRhcmRpa2EiLCJhIjoiY2xsb3Z5b3d6MDA5NDNmbnFzeHp6OGFwOSJ9.RUrg03_5uLOp17czGL8k2w'; // Ganti dengan token akses Mapbox Anda

let currentContext = {};

function fetchAddresses(query, resultElement) {
    const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${accessToken}&country=US`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const places = data.features;
            let resultsHtml = '';
            for (let place of places) {
                currentContext[place.place_name] = place.context;
                resultsHtml += `<div onclick="selectAddress('${place.place_name}', '${resultElement.id}')">${place.place_name}</div>`;
            }
            resultElement.innerHTML = resultsHtml;
            resultElement.style.display = 'block';
        });
}

function selectAddress(address, resultElementId) {
    const inputElementId = resultElementId.replace('-results', '-input');
    document.getElementById(inputElementId).value = address;
    document.getElementById(resultElementId).style.display = 'none';

    const context = currentContext[address];

    // Extract city, state, and zip from context
    let city = '';
    let state = '';
    let zip = '';
    for (let item of context) {
        if (item.id.startsWith('place')) {
            city = item.text;
        } else if (item.id.startsWith('region')) {
            state = item.text;
        } else if (item.id.startsWith('postcode')) {
            zip = item.text;
        }
    }

    // Set values to hidden fields
    document.getElementById(inputElementId.replace('-input', '-city')).value = city;
    document.getElementById(inputElementId.replace('-input', '-state')).value = state;
    document.getElementById(inputElementId.replace('-input', '-zip')).value = zip;
    checkCitiesAndDisplayError();
}

function checkCitiesAndDisplayError() {
    const pickupCity = document.getElementById('pickup-city').value;
    const destinationCity = document.getElementById('destination-city').value;
    const errorMessage = document.getElementById('error-message');

    if (pickupCity && destinationCity && pickupCity !== destinationCity) {
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
    }
}


document.getElementById('wpforms-461-field_4').addEventListener('input', (e) => {
    if (e.target.value.length > 2) {
        fetchAddresses(e.target.value, document.getElementById('pickup-results'));
    }
});

document.getElementById('wpforms-461-field_5').addEventListener('input', (e) => {
    if (e.target.value.length > 2) {
        fetchAddresses(e.target.value, document.getElementById('destination-results'));
    }
});

})( jQuery );
