<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sponsorship;
use App\Apartment;
use App\Image;
use App\Service;
use App\Message;
use App\Visit;

// il guest controller è dedicato agli utenti non registrati (le pagine sono accessibili anche ad utenti registrati)
class GuestController extends Controller
{

   // questa funzione restituisce la home del sito con delle card con gli appartamenti sponsorizzati
   public function index(){

      $date = date('Y-m-d');

      $apts = Sponsorship::where('end_date', '>', $date) // seleziono solo le sponsorship con date maggiore di now()
                              -> join('apartments', 'apartments.id', '=', 'sponsorships.apartment_id') // join con tabella apartment
                              -> where('apartments.visible', '=', 1)
                              -> groupBy('apartment_id') // siccome ogni singolo apartment può avere n sponsorizzazioni non ancora scadute raggruppo per id apartment in modo da non aver doppioni
                              -> select('apartments.*') // prendo tutti i dati dell'appartamento
                              -> get();
         
      foreach ($apts as $apt) { // ciclo sulla lista di appartamenti con promozione non scaduta
         
         $id = $apt -> id; // Ricavo l'id dell'appartamento

         $img = Image::where('apartment_id', '=', $id) -> first(); // prendo dalla tabella immagini la prima immagine con id corrsipondente. ATTENZIONE: Potrebbero anche non esserci immagini associate all'appartamento


         if ($img){ // se esiste almeno un immagine associata all'appartamento

            $apt['img'] = $img -> img; // creo un attributo 'img' nel singolo appartamento e lo valorizzo con l'url dell'immagine ($img contiene anche altri dati tipo id ecc. io prendo solo img che è l'url dell'immagine)

         } else {
            $apt['img'] = '/img/image-not-found.png';
         }
      }

      // apts conterrà una lista di appartamenti con promozione non ancora scaduta e l'url di un immagine nell attributo img
      return view('home', compact('apts'));
   }


   // ritorna la pagina che mostra i dettagli di un appartamento
   public function show($id) {

      Visit::create([
         'apartment_id' => $id
      ]); // ad ogni accesso prima di restituire la view creo un record della visita. associo l'id dell'appartamento laravel in automatico associa data e ora della creazione del record

      $apt = Apartment::findOrFail($id);

      $imgs = Image::where('apartment_id', '=', $id) -> get(); // mando al front anche le immagini dell'appartamento
      
      return view ('show', compact('apt', 'imgs'));
   }

 
   //funzione che gestisce la creazione di un messaggio
  public function storemsg(request $request, $id){

      $data = $request -> validate([
         'email' => 'required|email',
         'message' => 'required|min:3|max:1000'
      ]); // valido i dati in arrivo mail del mittente e testo del messaggio

      $data['apartment_id'] = $id;

      Message::create($data); // salvo il messaggio nel db. la colonna read (messaggio letto) di default è settata su false
  
      return back()-> with('status', 'Message send successfully'); // aggiorno la pagina segnalando che il messaggio è stato mandato correttamente
  
  }

  // questa funzione restituisce la view della ricerca. mando i dati ricercati nell'input della pagina home (verranno stampati nella pagina di ricerca e con js verrà lanciata una prima ricerca con filtri settati su valori di default)
  public function toSearch(request $request) {

   $srvs = Service::all(); // i servizi vengono mandati in modo da creare una serie di input checkbox per il filtro degli appartamenti

   $data = $request -> all();

   return view('search', compact('srvs', 'data'));
  }


}
