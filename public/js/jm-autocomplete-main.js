(function( $ ) {
    'use strict';

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

    window.selectAddress = function(address, resultElementId) {
    console.log("Function selectAddress called with address:", address, "and resultElementId:", resultElementId);

    let inputElementId;
    if (resultElementId === 'pickup-results') {
        inputElementId = 'wpforms-461-field_4';
    } else if (resultElementId === 'destination-results') {
        inputElementId = 'wpforms-461-field_5';
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
        const pickupCity = document.getElementById('wpforms-461-field_4-city').value;
        const destinationCity = document.getElementById('wpforms-461-field_5-city').value;
        const errorMessage = document.getElementById('error-message');

        console.log("Pickup inputElementId:", pickupCity );
        console.log("Destination inputElementId:", destinationCity );

        if (pickupCity && destinationCity && pickupCity !== destinationCity) {
            errorMessage.style.display = 'block';
            console.log("Result:", True );
        } else {
            errorMessage.style.display = 'none';
        }
    }

    $(document).ready(function() {
        $('#wpforms-461-field_4').on('input', function(e) {
            if ($(this).val().length > 2) {
                fetchAddresses($(this).val(), $('#pickup-results')[0]);
            }
        });

        $('#wpforms-461-field_5').on('input', function(e) {
            if ($(this).val().length > 2) {
                fetchAddresses($(this).val(), $('#destination-results')[0]);
            }
        });
    });

})( jQuery );
