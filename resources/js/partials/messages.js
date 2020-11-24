window.$ = window.jQuery = require('jquery');

$(document).ready(messages);

function messages(){

    allRead()
}

// questa funzione fa una chiamata ajax alla url /allRead. Il controller prenderà grazie alla Auth:user() l'id dell'utente loggato in questo momento e setterà il valore di read di tutti i messaggi di tutti gli appartamenti di quell'utente su 1 (true). Così tutti i messaggi di quell'utente risulteranno letti.
function allRead(){

    $.ajax({
       url: '/allRead',
       method: 'GET',
       success: function(data){
           console.log(data);
       },
       error: function(err){
           console.log(err);
       }
    });
}
