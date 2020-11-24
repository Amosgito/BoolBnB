window.$ = window.jQuery = require('jquery'); // importa la libreria jquery

$(document).ready(autocomplete) 



 function autocomplete() { 

    
    
    // crea l'autocompletamento fornito con la libreria di algolia places sull'input '#search-address-input'
    var placesSearchAutocomplete = places({

        appId: 'pl9FR7QVJTYP',
        apiKey: '8240a6d46c9f2914027ee977cb8aeeb3',
        container: document.querySelector('#search-address-input')
        
    })
    
    
    // ad ogni cambiamento dell'input con l'autocompletamento va a salvare in due input nascosti i valori di latitutdine e longitudine.
    // e.suggestion.latlng serve per prendere gli attributi con i valori di lat e lon dall'oggetto creato da algolia places
    placesSearchAutocomplete.on('change', function (e) {
        
        var latitude = e.suggestion.latlng.lat
        var longitude = e.suggestion.latlng.lng

        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
    })

 }