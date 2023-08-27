(function( $ ) {
    'use strict';

    const accessToken = jmAutocompleteData.mapboxApiKey;
    let currentContext = {};
    let formID = jmAutocompleteData.formId;
    let pickupField = jmAutocompleteData.pickupField;
    let destinationField = jmAutocompleteData.destinationField;

    // Inisialisasi MapboxGeocoder untuk Pickup
    const pickupGeocoder = new MapboxGeocoder({
        accessToken: accessToken,
        mapboxgl: mapboxgl,
        countries: 'us',
        types: 'address,place,postcode',
        placeholder: 'Enter an address',
        marker: false,
        minLength: 3
    });

    // Inisialisasi MapboxGeocoder untuk Destination
    const destinationGeocoder = new MapboxGeocoder({
        accessToken: accessToken,
        mapboxgl: mapboxgl,
        countries: 'us',
        types: 'address,place,postcode',
        placeholder: 'Enter an address',
        marker: false,
        minLength: 3
    });

    // Menempelkan Geocoder ke elemen input
    document.getElementById(pickupField).appendChild(pickupGeocoder.onAdd());
    document.getElementById(destinationField).appendChild(destinationGeocoder.onAdd());

    // Mendengarkan event 'result' dari Geocoder
    pickupGeocoder.on('result', function(e) {
        selectAddress(e.result.place_name, 'pickup-results');
    });

    destinationGeocoder.on('result', function(e) {
        selectAddress(e.result.place_name, 'destination-results');
    });

    window.selectAddress = function(address, resultElementId) {
    console.log("Function selectAddress called with address:", address, "and resultElementId:", resultElementId);

    let inputElementId;
    if (resultElementId === 'pickup-results') {
        inputElementId = pickupField;
    } else if (resultElementId === 'destination-results') {
        inputElementId = destinationField;
    } else {
        console.error("Unknown resultElementId:", resultElementId);
        return;
    }
    console.log("Determined inputElementId:", inputElementId);

    document.getElementById(inputElementId).value = address;
    document.getElementById(resultElementId).style.display = 'none';

    const context = currentContext[address];
    console.log("Context for the address:", context);

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

    console.log("Extracted city:", city, "state:", state, "zip:", zip);

    // Set values to hidden fields
    document.getElementById(inputElementId + '-city').value = city;
    document.getElementById(inputElementId + '-state').value = state;
    document.getElementById(inputElementId + '-zip').value = zip;
    checkCitiesAndDisplayError();
    }

    function checkCitiesAndDisplayError() {
        const pickupCity = document.getElementById(pickupField + '-city').value;
        const destinationCity = document.getElementById(destinationField + '-city').value;
        const errorMessage = document.getElementById('error-message');
        const submitButton = document.getElementById('wpforms-submit-' + formID);

        console.log("Pickup inputElementId:", pickupCity );
        console.log("Destination inputElementId:", destinationCity );

        if (pickupCity && destinationCity && pickupCity !== destinationCity) {
            errorMessage.style.display = 'block';
            console.log("Result: True");
            submitButton.disabled = true;
        } else {
            errorMessage.style.display = 'none';
            console.log("Result: False");
            submitButton.disabled = false;
        }
    }

})( jQuery );