(function( $ ) {
    'use strict';

    const accessToken = jmAutocompleteData.mapboxApiKey; // Ganti dengan token akses Mapbox Anda
    mapboxgl.accessToken = accessToken;
    let currentContext = {};
    let formID = jmAutocompleteData.formId;
    let pickupField = jmAutocompleteData.pickupField;
    let destinationField = jmAutocompleteData.destinationField;
    let maxRadiusField = jmAutocompleteData.maxRadiusField;

    let map;
    let mapPopup;
    let markerPopup;

    // Fungsi untuk melakukan reverse geocoding
    function reverseGeocode(lngLat, callback) {
        const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${accessToken}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.features && data.features.length > 0) {
                    const address = data.features[0].place_name;
                    callback(address);
                } else {
                    callback(null);
                }
            })
            .catch(error => {
                console.error("Error during reverse geocoding:", error);
                callback(null);
            });
    }

    function showMapPopup(event) {
        event.preventDefault();

        if ($('#popup-map').length === 0) {
            console.error("Element with ID 'map' not found.");
            return;
        }

        // Cek apakah peta sudah ada
        if (!mapPopup) {
            mapboxgl.accessToken = accessToken;
            mapPopup = new mapboxgl.Map({
                container: 'popup-map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [-96, 37.8],
                zoom: 6
            });

            // Tambahkan kontrol navigasi ke peta
            mapPopup.addControl(new mapboxgl.NavigationControl());

             // Tunggu peta selesai dimuat
            mapPopup.on('load', function() {
                // Ambil koordinat tengah dari peta
                const center = mapPopup.getCenter();

                // Tambahkan marker yang dapat digerakkan ke peta di koordinat tengah
                markerPopup = new mapboxgl.Marker({
                    draggable: true
                })
                .setLngLat(center)
                .addTo(mapPopup);
            });
        }

        $('#map-popup').show();

        // Tambahkan event listener untuk tombol "done"
        $('#done-button').on('click', function(event) {
            event.preventDefault(); // Menghentikan tautan dari navigasi ke URL
            const selectedCoordinates = marker.getLngLat();
            window.pickupCoordinates = [selectedCoordinates.lng, selectedCoordinates.lat];
            const lngLat = markerPopup.getLngLat();
        
            // Panggil fungsi reverse geocoding
            reverseGeocode(lngLat, function(address) {
                if (address) {
                    $('#'+pickupField).val(address);
                } else {
                    alert("Unable to fetch address for the selected location.");
                }
                $('#map-popup').hide();
            });
        });
    }

    $(document).ready(function() {
        // Tambahkan tautan di samping field pickup
        $('#'+pickupField).after('<a href="#" id="select-pin-link">Select Pin on Map</a>');

        // Tambahkan event listener untuk tautan "select pin on map"
        $('#select-pin-link').on('click', showMapPopup);
    });

    // Inisialisasi Peta
    function initializeMap() {
        map = new mapboxgl.Map({
            container: 'directions-map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [-96, 37.8],
            zoom: 3
        });
    }

     // Menambahkan Garis Arah ke Peta
    function addDirectionToMap(pickupCoordinates, destinationCoordinates) {
        // Jika pickupCoordinates atau destinationCoordinates kosong, keluar dari fungsi
        if (!pickupCoordinates || !destinationCoordinates) {
            console.log("Either pickupCoordinates or destinationCoordinates is empty. Exiting...");
            return;
        }

        if (map.getSource('route')) {
            map.removeLayer('route');
            map.removeSource('route');
        }

        map.addSource('route', {
            'type': 'geojson',
            'data': {
                'type': 'Feature',
                'properties': {},
                'geometry': {
                    'type': 'LineString',
                    'coordinates': [pickupCoordinates, destinationCoordinates]
                }
            }
        });

        map.addLayer({
            'id': 'route',
            'type': 'line',
            'source': 'route',
            'layout': {
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': '#888',
                'line-width': 8
            }
        });
    }

    $(document).ready(function() {
        // Pastikan elemen 'directions-map' ada sebelum menginisialisasi peta
        if ($('#directions-map').length) {
            initializeMap();
        }

        // Anda bisa memanggil addDirectionToMap di sini atau di tempat lain
        // berdasarkan koordinat pickup dan destinasi yang Anda dapatkan
        // contoh:
        // addDirectionToMap([longitudePickup, latitudePickup], [longitudeDestination, latitudeDestination]);
    });


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

    let inputElementId;
    let pickupCoordinates_point; // Gunakan let di sini
    let destinationCoordinates_point; // Gunakan let di sini

    window.selectAddress = function(address, resultElementId) {
    console.log("Function selectAddress called with address:", address, "and resultElementId:", resultElementId);

    
    if (currentContext[address] && currentContext[address].geometry) {
        if (resultElementId === 'pickup-results') {
        window.pickupCoordinates = currentContext[address].geometry.coordinates;
        pickupCoordinates_point = currentContext[address].geometry.coordinates;
        inputElementId = pickupField;
        } else if (resultElementId === 'destination-results') {
            window.destinationCoordinates = currentContext[address].geometry.coordinates;
            destinationCoordinates_point= currentContext[address].geometry.coordinates;
            inputElementId = destinationField;
        } else {
            console.error("Unknown resultElementId:", resultElementId);
            return;
        }
    }
    console.log("Determined inputElementId:", inputElementId);
    console.log("1p:", pickupCoordinates_point);
    console.log("2d:", destinationCoordinates_point);

    addDirectionToMap(pickupCoordinates_point, destinationCoordinates_point);

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

        //console.log("Pickup inputElementId:", pickupCity );
        //console.log("Destination inputElementId:", destinationCity );

        // Cari elemen <em> untuk pickupField dan destinationField
        let pickupErrorElement = document.getElementById(pickupField + '-error-radius');
        let destinationErrorElement = document.getElementById(destinationField + '-error-radius');

        // Jika elemen <em> tidak ada, buat elemen baru
        if (!pickupErrorElement) {
            pickupErrorElement = document.createElement('em');
            pickupErrorElement.id = pickupField + '-error-radius';
            pickupErrorElement.className = 'wpforms-error-radius';
            pickupErrorElement.setAttribute('role', 'alert');
            pickupErrorElement.setAttribute('aria-label', 'Error message');
            pickupErrorElement.style.display = 'none';
            document.getElementById(pickupField).parentNode.appendChild(pickupErrorElement);
        }

        if (!destinationErrorElement) {
            destinationErrorElement = document.createElement('em');
            destinationErrorElement.id = destinationField + '-error-radius';
            destinationErrorElement.className = 'wpforms-error-radius';
            destinationErrorElement.setAttribute('role', 'alert');
            destinationErrorElement.setAttribute('aria-label', 'Error message');
            destinationErrorElement.style.display = 'none';
            document.getElementById(destinationField).parentNode.appendChild(destinationErrorElement);
        }

        if (window.pickupCoordinates && window.destinationCoordinates) {
            const distance = haversineDistance(window.pickupCoordinates, window.destinationCoordinates);
            if (distance > maxRadiusField) { // 30 mil dalam kilometer
                destinationErrorElement.textContent = errorMessage.textContent;
                destinationErrorElement.style.display = 'block';
                destinationErrorElement.style.color = '#d63637';
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
