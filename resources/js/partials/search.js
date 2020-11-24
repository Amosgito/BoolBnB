window.$ = window.jQuery = require('jquery');

const Handlebars = require("handlebars");

$(document).ready(search)

function search() {

    autocomplete();
    distanceSlider();
    
    addSearchButtonListener();
    addShowListener();
    
    sendRequestSearch();

    addResizeListener();
}


// questa funzione crea l'autocompletamento della ricerca grazie alla libreria algolia places inoltre ad ogni ricerca salva i dati di latitudine e longitudine nascosti in due span con display: none;
function autocomplete() {

    var placesSearchAutocomplete = places({

        appId: 'pl9FR7QVJTYP',
        apiKey: '8240a6d46c9f2914027ee977cb8aeeb3',
        container: document.querySelector('#search')

    })

    placesSearchAutocomplete.on('change', function (e) {

        var latitude = e.suggestion.latlng.lat
        var longitude = e.suggestion.latlng.lng

        $('#latitude').data('number', latitude);
        $('#longitude').data('number', longitude);
    })

}

// listener del bottone che lancia la ricerca
function addSearchButtonListener(){

    $('#search-button').click(sendRequestSearch);
}


// la ricerca viene effettuata con un ajax. 
function sendRequestSearch() {

    // si prende tutti i valori degli input lat, lon distanza massima, servizi richiesti ecc e li manda al controller che restituisce gli appartamenti già filtrati in modo che corrispondano alle richieste dell'utente
   var lat = $('#latitude').data('number');
   var lon = $('#longitude').data('number');

   var dist = $('#distance').val();

   var services = [];

    $("input[name='service[]']:checked").each(function (){

        services.push($(this).val());

    });
    
    srvs = services.join(); // l'array con gli id dei servizi viene mandato in forma di stringa. nel backend verrà ritradotto in un array

   var rooms = $('#rooms').val();
   var beds = $('#beds').val();

    $.ajax({

        url: '/api/search',
        data: {
            'lat' : lat,
            'lon': lon,
            'dist': dist,
            'srvs': srvs,
            'rooms': rooms,
            'beds': beds
        },
        method: 'GET',
        success: function(data) {

            if(data.length == 0){
                // in caso non ci siano risultati stamo no results
                $('#sponsored').html('');
                $('#standard').html('<div class="col-12"><h3 class="py-5 text-primary text-center">No results</h3></div>');
            } else {
                // in caso ci siano risultati stampo le card con i risultati di ricerca
                printCards(data);
                printServiceIcon(data);
                resizeImages();
            }

        },
        error: function(err) {

            console.log('err', err);
        }

    })
}

// funzione che serve solamente ad aggiornare il valore visualizzato con l'effettivo valore dello slider della distanza (ogni volta che muovo lo slider aggiorno il valore visualizzato)
function distanceSlider(){

    var $valueSpan = $('.valueSpan');
    var $value = $('#distance');

    $valueSpan.html($value.val());

    $value.on('input change', function() {
        
        $valueSpan.html($value.val());
    });
}

// print card stampa i risultati della ricerca usando handlebars come template compiler
function printCards(data){

    // gli appartamenti sponsorizzati verrano stampati sopra e con un bordo diverso. per questo uso due contenitori differenti per appartamenti sponsorizzati e non
    var targetSponsored = $('#sponsored');
    var targetStandard = $('#standard');

    // cancello i risultati di eventuali ricerca precedenti
    targetSponsored.html('');
    targetStandard.html('');
   
    var template = $('#apt-template').html()

    var compiled = Handlebars.compile(template);

    $.each(data, function(index, apt){

        // cambio il target a seconda della sponsorizzazione
        if(apt['sponsorship'][0] == true){

            var target = targetSponsored;
        } else {

            var target = targetStandard;
        }

        // compilo la card e appendo
        var html = compiled(apt);

        target.append(html);

    });
}

// questa funzione fa diventare ogni card cliccabile e gli assegna un link alla pagina show dell'appartamento della card
function addShowListener(){

   $(document).on('click', '.show-apt-link', function(){
       
       var id = $(this).data('id');
       window.location.href = "/show/" + id;
   });

}


// non potendo fare un ciclo nel template handlebars dopo aver stampato le card ripasso i risultati della ricerca per stampare le icone dei servizi
function printServiceIcon(data){

    // per ogni appartamento risultato dalla ricerca
    $.each(data, function(index, apt){

        var target = $('.show-apt-link[data-id="' + apt['id'] + '"]'); // riconosco la card grazie all'id stampato nel data-id

        var listTarget = target.find('.service-icons-list'); // trovo il contenitore delle icone

        var srvsIcons = apt['srvsIcons']; // l'array con le icone compilato nel backend con le icone dei servizi presenti nell'appartamento

        for (var i = 0; i < srvsIcons.length; i++) { // stampo ogni icona presente
           
            var icon = '<li class="list-inline-item px-1"><i class="'+ srvsIcons[i] + '"></i></li>';

            listTarget.append(icon);
            
        }

    });
}


// per avere immagini tutte uguali nel frontend c'è un div .image-faker che ha come sfondo l'immagine dell'appartamento.
// il div ha width 100% che dipenderà dalle dimensioni della finestra. con questa funzione gli assegno una height uguale alla width in questo modo avrò dei quadrati con come sfondo l'immagine dell'appartamento (nel css è stata messo backgrond-size: cover; quindi il quadratino sarà "riempito" con l'immagine (che magari non sarà completamente visibile))
function resizeImages(){

    var target = $('.image-faker');

    target.each(function(){
        
        var w = $(this).width();

        $(this).css('min-height', w);
    });
}

// ad ogni resize della finestra aggiorno l'altezza dei div .image-faker in modo da mantenerli quadrati
function addResizeListener(){

    $(window).resize(resizeImages);
}