window.$ = window.jQuery = require('jquery');

$(document).ready(header) 


function header() {

    headerFunciton()
    scrollLogo()
}

// questa funzione mostra/nasconde il menu del profilo al click sul tasto menu hamburger, cambia l'icona, toglie la notifica nuovo messaggio 
function headerFunciton() {


        // Toggle nav menu
        $(document).on('click', '.nav-toggle', function(e) {
          $('.nav-menu').toggleClass('nav-menu-active');
          $('.nav-toggle').toggleClass('nav-toggle-active');
          $('.nav-toggle i').toggleClass('fa-times');
          $('#hamburger-dot').toggle();
      
        });
      

}

// questa funzione allo scroll della pagina cambia il logo
function scrollLogo() {

  // il logo è composto da due immagini loghino più scritta. allo scroll della finestra (window) se scrollTop() cioè quanti pixel ho scrollato dall'alto è >10 nascondo la scritta. se è <10 cioè son tornato su ricompare la scritta
    $( window ).scroll(function() {

      if($(window).scrollTop() > 10) {

        $( "#logo2" ).fadeOut()
        
      }else {

        $( "#logo2" ).fadeIn()   
      }
  })
}
