window.$ = window.jQuery = require('jquery');

$(document).ready(create) 


function create() {

    placesCreateAutocomplete();
    frontEndValidation();
    $('input').each(checkinput);
    $('textarea').each(checkinput);
    
}

function placesCreateAutocomplete(){ // funzione che crea l'autocomplete nella ricerca. si basa su un servizio di algolia

    var placesCreateAutocomplete = places({
    
        appId: 'pl9FR7QVJTYP', // questi sono chiavi che ti danno alla registrazione al sito. Limite di 10.000 ricerche al mese poi si paga.
        apiKey: '8240a6d46c9f2914027ee977cb8aeeb3',
        container: document.querySelector('#address-input') // qua si seleziona l'input su cui applicare l'autocomplete. In questo caso l'input dell'indirizzo per la create
        
    });
    
    placesCreateAutocomplete.on('change', function (e) { // funzione che si attiva ad ogni change dell'input su cui abbiamo fatto l'autocomplete
            
        var latitude = e.suggestion.latlng.lat // dall'oggetto si va a cercare negli attributi latitudine e longitudine e si salvano nelle variabili
        var longitude = e.suggestion.latlng.lng
    
        $('#longitude').val(longitude); // latitudine e longitudine vengono salvate in degli input nascosti con display none; in questo modo fanno parte del form anche se l'utente non lo sa
        $('#latitude').val(latitude);
    });

}

function frontEndValidation(){

    // per tutti gli input e per la textarea della description sia al keyup(alla pressione di un tasto sulla tastiera) che al change(altro evento che fa cambiare il valore dell'input ad esempio un click sui suggerimenti della ricerca) si lancia la funzione checkinput
    $('input').change(checkinput);
    $('input').keyup(checkinput);
    $('textarea').keyup(checkinput);
    $('textarea').change(checkinput);
}

function checkinput() {

    // la funzione checkinput divide le situazioni in due. se l'input ha l'attributo type="file" (in sostanza è l'input di upload delle immagini) fa delle verifiche altrimenti su input testuali e numerici ne fa altre. per ognuna delle due possibilità c'è una funzione a cui verrà passata l'istanza this che contiene tutti dei dati dell'input su cui si è scatenato l'evento keyup o change
    if($(this).attr('type') == 'file'){

        checkImages(this);
       
    } else {

        checkNotImagesInput(this);
    }
   
}

function checkNotImagesInput(value){

    // ATTENZIONE: la validità dell'input dipende dai criteri messi nell'html. (es. <input type="number" min="1" max="10" required>). questa funzione semplicemente colora degli span in cui ho specificato i range di validità dell'input in modo che l'utente sappia quali sono i limiti. Questa funzione NON disabilita la submit. tuttavia in caso di input non validi la submit è disabilitata di default e il browser crea un tooltip con un indicazione per l'utente (tipo vignetta "questo campo è richiesto"). 
    
    //Questo tooltip si può disabilitare in js con 

    // document.querySelector( "input" ).addEventListener( "invalid",
    //     function( event ) {
    //         event.preventDefault();
    //     });



    if($(value).is('#address-input')){ // siccome algolia crea in automatico l'autocomplete ma cambia un po' l'html creo due target diversi. Il target sarà comunque uno span che cambia colore se l'input è valido o no. value è l'oggetto this che ci arriva dalla funzione precedente

        var target = $('.address.validity');

    } else {

        var target = $(value).next('.validity');
    }


    if($(value).val() == 0){ // se l'input è vuoto assegno colore grigio

        target.removeClass('text-success text-danger');
        target.addClass('text-muted');

    } else{
        
        var val = value.checkValidity(); // questa funzione restituisce true se l'input è valido. false se non lo è. I criteri per ritenere l'input valido sono nell'html <input min="1" max="10" required>

        if(!val){ // input non valido coloro di rosso

            target.removeClass('text-success text-muted');
            target.addClass('text-danger');

        } else { // input valido coloro di verde

            target.removeClass('text-danger text-muted');
            target.addClass('text-success');

        }
    }

}

function checkImages(value){

    //ATTENZIONE: questa funzione non solo colora il testo dello span ma disabilita il tasto submit se l'input di upload delle immagini non è valido

    var target = $(value).next('.validity'); // il target è sempre lo span con classe validity. cambierà il colore del testo a seconda se l'input immagini è valido o no

    // le condizioni di validità dell'input immagini sono 3:
    // - il file è un immagine (e non un altro tipo di file)
    // - le immagini caricate non sono più di 5
    // - ogni singola immagine non è più grande di 2 MB

    if(value.files.length == 0){ // value è il this che ci arriva della funzione precedente. se la lenght è 0 vuol dire che non contiene file. Quindi ripristino il colore grigio dello span. e riattivo il bottone submit

        target.removeClass('text-success text-danger');
        target.addClass('text-muted'); 
        $('#create-submit').prop("disabled",false); 

    } else if(value.files.length > 5){ // se ci sono più di 5 file coloro di rosso lo span e disattivo il bottone submit

        target.removeClass('text-success text-muted');
        target.addClass('text-danger');
        $('#create-submit').prop("disabled",true);

    } else {

        // se c'è almeno un file e non più di 5. Controllo che i file siano del tipo immagine e che non siano più grandi di 2 MB.
        // setto le variabili tooBig (troppo grande) e wrongType (tipo sbagliato di file) su falso
        var tooBig = false;

        var acceptedType = [ // array con una lista di tipi di file accettati
            "image/apng",
            "image/bmp",
            "image/gif",
            "image/jpeg",
            "image/pjpeg",
            "image/png",
            "image/svg+xml",
            "image/tiff",
            "image/webp",
            "image/x-icon"
          ];

        var wrongType = false;

        // ATTENZIONE: input file fornisce in un oggetto una serie di dati tra cui il tipo di file e le dimensioni. L'array dei file è in this.files (in questo caso value.files). Ciclo su tutti i files

        for (var i = 0; i < value.files.length; i++) {
        
            // ciclando su tutti i files all'attributo .size trovo la dimensione in bytes controllo che ogni file non sia più grande di 2097152 bytes(che sono 2MB). all'attributo .type trovo il tipo di file (l'estendione) controllo che faccia parte dell'array delle estensioni accettate. finiti i controlli se almeno un file è troppo grande o del tipo sbagliato coloro di rosso lo span e disabilito il tasto submit. altrimenti semaforo verde e via
           if(value.files[i].size > 2097152) {

               tooBig = true;
            }

            if(!acceptedType.includes(value.files[i].type)){

                wrongType = true
            }
        }

        
        if(tooBig == true || wrongType == true){

            target.removeClass('text-success text-muted');
            target.addClass('text-danger');
            $('#create-submit').prop("disabled",true);

        } else {

            target.removeClass('text-danger text-muted');
            target.addClass('text-success');
            $('#create-submit').prop("disabled",false);
        }

    }
}
