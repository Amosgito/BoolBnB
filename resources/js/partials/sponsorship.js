window.$ = window.jQuery = require('jquery');

$(document).ready(sponsorship);

function sponsorship(){
    
    $('.choose').on('click', choosePromotion);
}

function choosePromotion(){

    // mostro graficamente quale promozione è stata scelta
    $('.card.promo').removeClass('fixed-hover');
        
    $(this).parents('.card.promo').addClass('fixed-hover');
    

    // valorizzo l'input nascosto con l'id della promozione selezionata
    var id = $(this).data('id');

    var targetId = $('#promo_id');
    
    targetId.val(id);

    // stampo la card con i dettagli dell'ordine
    
    var targetPrice = $('#details-price');

    var targetDate = $('#details-ending');

    var price = $(this).parents('.promo').data('price');
    var hours = $(this).parents('.promo').data('hours');
    
    var endDate = getEndDate(hours);
    
    targetPrice.text(price);

    targetDate.text(endDate);

    $('#order-container').removeClass('d-none');
}


// questa funzione prende la start date (che è stampata in un input nascosto e può essere oggi o la fine della sponsorizzazione ancora attiva) e ci somma le ore della promozione selezionata restituendo la data di fine della promozione scelta
function getEndDate(hours){

    var startDate = $('#start_date').val(); // questa data e ora passati dal backend hanno formato yyyy-mm-dd H:i

    var forFirefoxDate = startDate.replace(/-/g, '/'); // usando firefox non ti permette con Date(startDate) di ottenere l'istanza dell'oggetto Date perchè non gli piace il formato yyyy-mm-dd (in chrome funzionerebbe tutto tranquillamente!) allora con replace cambio la stringa di startDate da yyyy-mm-dd a yyyy/mm/dd (replace sostituisce nella stringa con /-/g sostituisce TUTTI i "-" se avessi scritto replace('-', '/') sostituiva solo il primo.) Il formato yyyy/mm/dd piace sia a firefox che a chrome. Non si poteva stamparlo così direttamente dal backend perchè poi quasta data torna al backend attraverso il form e il backend invece non riconosce il formato con / al posto di -

    var endDate = new Date(forFirefoxDate); // new Date crea un oggetto Data di js. Date() senza argomenti crea un oggetto data e ora di adesso. passandogli una stringa si crea l'oggetto della data e ora passata con la stringa e si può utilizzare per esempio per scrivere se quel giorno è un lunedì o un giovedì (e un milione di altri casi d'uso)


    endDate.setHours( endDate.getHours() + hours ); // aggiungo le ore

    // formattedEndDate compone una stringa con la data bella da visualizzare a schermo
    var formattedEndDate =  appendLeadingZeroes(endDate.getDate()) + "/" +  appendLeadingZeroes(endDate.getMonth() + 1) + "/" + endDate.getFullYear() + " " +  appendLeadingZeroes(endDate.getHours()) + ":" +  appendLeadingZeroes(endDate.getMinutes());
       
    return formattedEndDate;
}

// questa funzione aggiunge uno zero davanti a un numero < 9. la uso per avere date 01/05/2020 invece di 1/5/2020
function appendLeadingZeroes(n){
    if(n <= 9){
      return "0" + n;
    }
    return n;
};