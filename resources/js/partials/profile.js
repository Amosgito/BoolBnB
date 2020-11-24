window.$ = window.jQuery = require('jquery');

$(document).ready(profile);

function profile(){

    addDeleteBtnListener();

    addSwitchVisibilityListener();

    //disableSponsorButtons();
}

// questa funzione mostra una modale che chiede conferma del voler cancellare l'appartamento
function addDeleteBtnListener(){

    // il tasto delete della pagina profile è legato ad una modale attraverso le automazioni di bootstrap.
    // quando viene mostrata la modale si attiva una funzione che compila la modale con il nome dell'appartamento e assegna al bottone delete della modale un link all'url di cancellazione dell'appartamento
    $('#deleteModal').on('show.bs.modal', function (e) {

        var href = '/delete/' + $(e.relatedTarget).attr('data-id');

        var title = $(e.relatedTarget).attr('data-title');


        var target = $('#deleteModal');

        target.find('#apt-title').text(title);

        target.find('#confirm-delete-btn').attr('href', href);
      });
}


// questa funzione cambia il valore di 'visible' dell'appartamento.
function addSwitchVisibilityListener() {

    var target = $('.visibilitySwitch'); // questo è un checkbox di bootstrap che ha un piccolo slide invede del quadratino con la spunta

    target.change(function(){ // al cambio di valore si attiva la funzione

        // setto visible su 0 o 1 a senconda se la checkbox è checcata o no
        var visible = 0; 
        
        if($(this).is(':checked')){
            visible = 1;
        }

        var usrId = $(this).data('usrid');

        var aptId = $(this).data('aptid');

        // con un ajax mando la richiesta. Nei data metto il valore di visible e l'id dell'appartamento da modificare. Per avere un controllo ulteriore metto l'id dello user loggato. in questo modo un eventuale 'malintenzionato' per cambiare la visibilità di un appartamento dovrebbe sapere l'id dell'appartamento e l'id dello user corrispondente. non è impossibile ma meglio di niente
        $.ajax({
            url: '/setVisibility',
            method: 'GET',
            data:{
                'usrId': usrId,
                'aptId': aptId,
                'visible': visible,
            },
            success: function(data){

                // in pagina non mostro niente. non faccio nemmeno un refresh. ci sarà in console un success
                console.log(data);
            },
            error:function(err){

                // in caso di errore faccio un alert e ricarico la pagina
                
                alert("Can't change visibility. Try again");
                location.reload();
            }
        });


    });
}


// questa funzione veniva usata per disabilitare il tasto sponsor nel caso in cui ci fosse stata una sponsorizzazione ancora attiva. é stata commentata da quando abbiamo creato la possibilità di aggiungere ore a sponsorizzazioni già attive
function disableSponsorButtons(){

    var target = $('.sponsor-button');

    target.each(function(){

        var dataSponsor = $(this).data('sponsor');

        if(dataSponsor != ""){

            $(this).addClass("disabled");
        }
    });
}
