<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;
use App\Sponsorship;
use App\Image;
use App\Visit;
use App\Message;

// questo controller si occupa di creare API di risposta a chiamate ajax

class ApiController extends Controller
{

   // funzione per la ricerca di appartamenti
    public function search(request $request){

        // prendo tutti i paramentri di ricerca (saranno sempre presenti anche solo con valori di default)
        $data = $request -> all();

        $lat = $data['lat'];
        $lon = $data['lon'];
        $dist = $data['dist'];
        $rooms = $data['rooms'];
        $beds = $data['beds'];
        $srvs = $data['srvs'];

        // i servizi selezionati come paramentro di ricerca arrivano come stringa. Se almeno un servizio è stato selezionato trasformo la stringa in un array di valori corrispondenti all'id dei servizi
        if($srvs != null){

            $arraySrvs = explode(',', $srvs);
        } else {
            // se nemmeno un servizio è selezionato array vuoto
            $arraySrvs = [];
        }


        $apts = Apartment::where('visible', '=', '1') -> get(); // prendo tutti gli appartamenti che hanno setteto la visibilità su true poi scremerò attraverso i parametri di ricerca

        foreach ($apts as $apt) {
            
            $distance = distance($apt['latitude'], $apt['longitude'], $lat, $lon); // calcolo la distanza (è una funzione matematica scritta sotto) tra lat e lon cercate e lat e lon dell'appartamento poi salvo un nuovo attributo dell'oggetto appartamento con il valore della distanza 

            $apt['distance'] = $distance;

            $services = Apartment::findOrFail($apt['id']) -> services() -> get(); // prendo tutti i servizi che ha l'appartamento

            $aptSrvs = [];
            $srvsIcons = [];

            foreach ($services as $service) { // ciclo su tutti i servizi e creo due array. uno con una serie di id del servizio l'altro con tutte le relative icone fontawesome

                $aptSrvs[] = $service['id'];
                $srvsIcons[] = $service['icon'];
            }

            $containsAllValues = !array_diff($arraySrvs, $aptSrvs); // controllo che l'array id servizi dell'appartamento contenga tutti i valori dell'array di servizi richiesti dalla ricerca

            // aggiungo due attributi all'oggetto appartamento che indicano se ha tutti i servizi richiesti e le icone fontawesome di tutti i servizi che ha
            $apt['hasAllServices'] = $containsAllValues; // true se tutti i servizi richiesti sono presenti nell'appartamento altrimenti false

            $apt['srvsIcons'] = $srvsIcons;

            // cerco la sponsorizzazione con data massima dell'appartamento e controllo se è già scaduta (> di adesso). creo un nuovo attributo con sponsorship valida true o false e data di scadenza
            $endSponsorship = Sponsorship::where('apartment_id', '=', $apt['id']) -> max('end_date');

            if ($endSponsorship > date('Y-m-d h:m:sa')) { //data('Y-m-d h:m:sa') in assenza di altri parametri restituisce il giorno odierno

                $apt['sponsorship'] = [true, $endSponsorship];

            } else {

                $apt['sponsorship'] = [false, $endSponsorship];
            }

            $img = Image::where('apartment_id', '=', $apt -> id) -> first(); // prendo la prima immagine dell'appartamento metto l'url corrispondente (nella colonna img della tabella images) in un nuovo attributo. se non c'è metto l'url dell'immagine di default

            if($img){

                $apt['img'] = $img['img'];
            } else{

                $apt['img'] = "/img/image-not-found.png";
            }

        }

        // adesso che ho una collezione di apts arricchiti di nuovi attributi che aiutano nella ricerca e nella stampa poi faccio una selezione con una serie di condizioni (distanza numero letti ha i servizi ecc.), metto in ordine per distanza e mando al frontend in formato json
        $response = $apts -> where('distance', '<', $dist)
                            -> where('bed_qt', '>=', $beds)
                            -> where('room_qt', '>=', $rooms)
                            -> where('hasAllServices', '=', true);

        $response = $response -> sortBy('distance');

        //NOTE: potrebbe essere utile spezzettare la ricerca nel senso di fare una prima selezione di appartamenti e poi solo su quelli cercare immagini e sponsorship ecc. questo ottimizzerebbe le risorse
        return response() -> json($response);
    }

    // questa funzione cambia il valore visible dell'appartamento (nel front end ci sarà un form nascosto al tasto delete in realtà c'è il submit di un form)
    public function setVisibility(request $request){

        // prendo i dati
        $data = $request -> all();

        $aptId = $data['aptId'];
        $usrId = $data['usrId'];
        $visible = ['visible' => $data['visible']];

        $apt = Apartment::findOrFail($aptId); //cerco l'appartamento con id corrispondente

        // aggiorno il valore di visible
        if($apt -> user_id = $usrId){
            $apt -> update($visible);
        }

        // ritorno un json encode con success (il front end ignorerà il json in caso di successo. in caso di errore ricaricherà la pagina e mostrerà la visibilità non aggiornata)
        return json_encode(['success' => 'success']);
    }


    // funzione che restituisce le statistiche di un appartamento per creare dei grafici.
    public function getStats(request $request){

        $aptId = $request['id'];

        $response = [];

        for ($i=1; $i < 13; $i++) {  // ciclo 12 volte una per mese

            $msgs = Message::where('apartment_id', '=', $aptId); // prendo tutti i messaggi  e le visite relativi a quell'appartamento

            $visits = Visit::where('apartment_id', '=', $aptId);
            
            $response['msg'][] = $msgs -> whereMonth('created_at', $i) -> whereYear('created_at', 2020) -> count(); // pusho nell'array la somma dei messaggi e delle visite di quel mese

            $response['vis'][] = $visits -> whereMonth('created_at', $i) -> whereYear('created_at', 2020) -> count();
        }

        //otterrò due array con dodici numeri. rappresentano le visite per mese e i messaggi per mese. ritorno tutto con un json
    
        return response() -> json($response);
    }
};


// formula matematica per il calcolo della distanza in km tra due punti identificati dalle coordinate latitudine e longitudine
function distance($lat1, $lon1, $lat2, $lon2) { 
    $pi80 = M_PI / 180; 
    $lat1 *= $pi80; 
    $lon1 *= $pi80; 
    $lat2 *= $pi80; 
    $lon2 *= $pi80; 
    $r = 6372.797; // mean radius of Earth in km 
    $dlat = $lat2 - $lat1; 
    $dlon = $lon2 - $lon1; 
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2); 
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a)); 
    $km = $r * $c; 
    
    return $km; 
};