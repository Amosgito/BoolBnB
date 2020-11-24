window.$ = window.jQuery = require('jquery');

$(document).ready(iniz)

function iniz() {

    map()
}
       
// questa funzione crea in pagina la mappa. Utilizza la libreria gratuita mapbox
function map() { 

    // latitudine e longitudine sono messe in un data-number di due span nascosti in pagina. li prendo da lì e li passo alla creazione dell'oggetto mappa in modo che la mappa sia centrata su quel punto.
    var lat = $('#lat-secrt').data('number')
    var long = $('#log-secrt').data('number')

    mapboxgl.accessToken = 'pk.eyJ1IjoiYW1vc2dpdG8iLCJhIjoiY2tnamdtOG94MHZnZzJ4cW5vY2t5aXhzMiJ9.SFoX4ECpx8qVIgtK9D8hfg';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11', // stylesheet location
        center: [long, lat], // starting position [lng, lat]
        zoom: 9 // starting zoom
    })

    // il marker sempre sulle coordinate latitudine e longitudine crea sulla mappa un indicatore della posizione dell'appartamento
    var marker = new mapboxgl.Marker()
                .setLngLat([long, lat])
                .addTo(map);


}