(function( $ ) {
    'use strict';

    const accessToken = jmAutocompleteData.mapboxApiKey; // Ganti dengan token akses Mapbox Anda
    let currentContext = {};
    let formID = jmAutocompleteData.formId;
    let pickupField = jmAutocompleteData.pickupField;
    let destinationField = jmAutocompleteData.destinationField;
    let maxRadiusField = jmAutocompleteData.maxRadiusField;

    function fetchAddresses(query, resultElement) {
        const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${accessToken}&type=address,place,postcode,&country=US`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const places = data.features;
                let resultsHtml = '';
                for (let place of places) {
                    currentContext[place.place_name] = place;
                    resultsHtml += `<div onclick="selectAddress('${place.place_name}', '${resultElement.id}')">${place.place_name}</div>`;
                }
                resultElement.innerHTML = resultsHtml;
                resultElement.style.display = 'block';
            });
    }

    window.selectAddress = function(address, resultElementId) {
    console.log("Function selectAddress called with address:", address, "and resultElementId:", resultElementId);

    let inputElementId;
    if (currentContext[address] && currentContext[address].geometry) {
        if (resultElementId === 'pickup-results') {
        window.pickupCoordinates = currentContext[address].geometry.coordinates;
        inputElementId = pickupField;
        } else if (resultElementId === 'destination-results') {
            window.destinationCoordinates = currentContext[address].geometry.coordinates;
            inputElementId = destinationField;
        } else {
            console.error("Unknown resultElementId:", resultElementId);
            return;
        }
    }
    console.log("Determined inputElementId:", inputElementId);

    document.getElementById(inputElementId).value = address;
    document.getElementById(resultElementId).style.display = 'none';

    const context = currentContext[address];
    console.log("Context for the address:", context);


    checkCitiesAndDisplayError();
    }

    function haversineDistance(coords1, coords2) {
        function toRad(value) {
            return value * Math.PI / 180;
        }

        const R = 6371; // radius bumi dalam kilometer
        const dLat = toRad(coords2[1] - coords1[1]);
        const dLon = toRad(coords2[0] - coords1[0]);
        const lat1 = toRad(coords1[1]);
        const lat2 = toRad(coords2[1]);

        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }


    function checkCitiesAndDisplayError() {
        const pickupCity = document.getElementById(pickupField + '-city').value;
        const destinationCity = document.getElementById(destinationField + '-city').value;
        const errorMessage = document.getElementById('error-message');
        const submitButton = document.getElementById('wpforms-submit-' + formID);

        console.log("Pickup inputElementId:", pickupCity );
        console.log("Destination inputElementId:", destinationCity );

        // Cari elemen <em> untuk pickupField dan destinationField
        let pickupErrorElement = document.getElementById(pickupField + '-error-radius');
        let destinationErrorElement = document.getElementById(destinationField + '-error-radius');

        // Jika elemen <em> tidak ada, buat elemen baru
        if (!pickupErrorElement) {
            pickupErrorElement = document.createElement('em');
            pickupErrorElement.id = pickupField + '-error-radius';
            pickupErrorElement.className = 'wpforms-error';
            pickupErrorElement.setAttribute('role', 'alert');
            pickupErrorElement.setAttribute('aria-label', 'Error message');
            pickupErrorElement.style.display = 'none';
            document.getElementById(pickupField).parentNode.appendChild(pickupErrorElement);
        }

        if (!destinationErrorElement) {
            destinationErrorElement = document.createElement('em');
            destinationErrorElement.id = destinationField + '-error-radius';
            destinationErrorElement.className = 'wpforms-error';
            destinationErrorElement.setAttribute('role', 'alert');
            destinationErrorElement.setAttribute('aria-label', 'Error message');
            destinationErrorElement.style.display = 'none';
            document.getElementById(destinationField).parentNode.appendChild(destinationErrorElement);
        }

        if (window.pickupCoordinates && window.destinationCoordinates) {
            const distance = haversineDistance(window.pickupCoordinates, window.destinationCoordinates);
            if (distance > maxRadiusField) { 
                destinationErrorElement.textContent = errorMessage.textContent;
                destinationErrorElement.style.display = 'block';
                rrorMessage.style.display = 'none';
                console.log("Result: True");
                submitButton.disabled = true;
            } else {
                pickupErrorElement.style.display = 'none';
                destinationErrorElement.style.display = 'none';
                errorMessage.style.display = 'none';
                console.log("Result: False");
                submitButton.disabled = false;
            }
        }
    }


    $(document).ready(function() {
        $('#'+pickupField).on('input', function(e) {
            if ($(this).val().length > 1) {
                fetchAddresses($(this).val(), $('#pickup-results')[0]);
            }
        });

        $('#'+destinationField).on('input', function(e) {
            if ($(this).val().length > 1) {
                fetchAddresses($(this).val(), $('#destination-results')[0]);
            }
        });
    });



})( jQuery );
