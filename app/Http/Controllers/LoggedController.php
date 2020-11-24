<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;
use App\Service;
use App\Image;
use App\Message;
use App\Promotion;
use App\Sponsorship;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LoggedController extends Controller {

    // con il construct middleware('auth') questo controller è accessibile soltanto ad utenti registrati.
    // NOTE: si potrebbe implementare una funzione di filtro ulteriore (nel senso che l'utente deve essere registrato ma ad esempio per modificare un appartamento deve essere autenticato con l'id dello user proprietario dell'appartamento e non semplicemente loggato con un user qualsiasi). è comunque un caso limite, utilizzando il sito "normalmente" non capita mai di modificare dati di altri bisogna decidere di farlo con "malizia"
    public function __construct(){
    
        $this->middleware('auth');
    
    }

    // la funzione create restituisce semplicemente una view con un form per creare un nuovo appartamento
    public function create() {

        $srvs = Service::all();

        return view ('create', compact('srvs'));
    }


    // la funzione store prende i dati compilati dall'utente nel form della create e crea un nuovo appartamento
    public function store(request $request){ 

        // validazione dei dati in ingresso. tutti i dati che vorremo prendere da $data vanno messi nel validate (anche senza condizioni ma comunque devono comparire)
        // NOTE: visto che questa parte di validazione si ripete nella edit sarebbe meglio creare una variabile e usare quella invece di riscrivere
        $data = $request -> validate([
            'description'  => 'required|min:3|max:1000',                 
            'title'  => 'required|min:3|max:60',                 
            'address'  => 'required|min:3|max:230',                 
            'room_qt'  => 'required|numeric|min:1|max:20',                 
            'bathroom_qt'  => 'required|numeric|min:1|max:8',                 
            'bed_qt'  => 'required|numeric|min:1|max:50',                   
            'mq'  => 'required|numeric|min:15|max:5000',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'img' => 'max:5',
            'img.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'services' => 'max:5',
            'services.*' => 'numeric'              
        ],[
            'img.max' => "Can't upload more than :max images",
            'img.*.max' => "Images can't be more than 2MB",
            'img.*.mimes' => 'Only images files are allowed'
        ]);  

        $id = Auth::user() -> id;

        $data['user_id'] = $id;

        // creazione del nuovo appartamento nel database prendendo i dati dal form + aggiunto l'id dello user
        $newApt = Apartment::create($data);

        // se nel form si associano dei servizi all'appartamento si associano nella tabella ponte del database con la funzione attach(). alla key 'services' si troverà già un array di id dei servizi checkati nel form
        if(array_key_exists('services', $data)){
            
            $newApt -> services() -> attach($data['services']);

        }

       
        // se sono state caricate delle immagini con storeAs le salvo. l'url sarà composto da image{Id appartamento}/{nomefile}.{estensionefile}. verranno salvate sia nella cartella storage che poi riportate nella cartella public
        if($request -> hasFile('img')){

            $imgs = $data['img'];


            foreach ($imgs as $img) {

                $name = $img -> getClientOriginalName();
        
                $url = $img -> storeAs('images' . $newApt -> id, $name, 'public');
 
                // salvato il file nella cartella storage creo un record nella tabella immagini con l'url e l'id dell'appartamento
                $file = Image::create([
                     'img' => '/storage/' . $url,
                     'apartment_id' => $newApt -> id
                 ]);

            }
            
        }
        
        // ritorno la view del profile dell'utente con il messaggio di buon fine dell'operazione
        return redirect() -> route('profile', $id) -> with('status', 'Congratulations! Apartment created successfully');;
    }



    // la edit ritorna la view di un form per modificare i dati dell'appartamento. Il form dev'essere già precompilato con i dati "originali" quindi attraverso la compact mando i dati dell'appartamento, i servizi e le immagini associate
    public function edit($id) {

        $apt = Apartment::findOrFail($id);

        $services = $apt -> services() -> get();

        $aptSrvs = [];

        foreach ($services as $service) {

            $aptSrvs[] = $service['id'];
        }

        $srvs = Service::all();

        $imgs = Image::where('apartment_id', '=', $id) -> get();

        return view('edit', compact('apt', 'aptSrvs', 'srvs', 'imgs'));
    }



    // la funzione update salva le modifiche ai dati di un appartamento. funziona esattamente come la create tranne due cose: servizi e immagini
    public function update(request $request, $id){

        $data = $request -> validate([
            'description'  => 'required|min:3|max:1000',                 
            'title'  => 'required|min:3|max:60',                 
            'address'  => 'required|min:3|max:230',                 
            'room_qt'  => 'required|numeric|min:1|max:20',                 
            'bathroom_qt'  => 'required|numeric|min:1|max:8',                 
            'bed_qt'  => 'required|numeric|min:1|max:50',                   
            'mq'  => 'required|numeric|min:15|max:5000',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'img' => 'max:5',
            'img.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'services' => 'max:5',
            'services.*' => 'numeric' ,
            'imgDel',
            'imgDel.*' => 'numeric'            
        ],[
            'img.max' => "Can't upload more than :max images",
            'img.*.max' => "Images can't be more than 2MB",
            'img.*.mimes' => 'Only images files are allowed'
        ]);

        $apt = Apartment::findOrFail($id);

        // Se sono stati associati dei servizi all'appartamento si usa la funzione sync che aggiorna i servizi associati nella tabella ponte. sincronizza associando e dissociando i servizi a seconda di cosa l'utente ha selezionato. Se l'utente non ha selezionato nessun servizio si utilizza detach() che dissocia i servizi eventualmente presenti in precedenza
        if(array_key_exists('services', $data)){
            
            $apt -> services() -> sync($data['services']);

        } else {

            $apt -> services() ->detach();
        }
        $usrid = Auth::user() -> id;

        $apt-> update($data);

        // il caricamento immagini funziona come quello nella create
        if($request -> hasFile('img')){

            $imgs = $data['img'];


            foreach ($imgs as $img) {

                $name = $img -> getClientOriginalName();
        
                $url = $img -> storeAs('images' . $apt -> id, $name, 'public');
 
                $file = Image::create([
                     'img' => '/storage/' . $url,
                     'apartment_id' => $apt -> id
                 ]);

            }    
        }

        // nella edit c'è una lista di checkbox aggiuntive per poter eliminare le immagini. Se ne è stata selezionata qualcuna si cicla sull'array di id delle immagini da cancellare con findOrFail si trova quell'immagine. con unlink si cancella fisicamente il file. con delete() si cancella la riga nella tabella immagini con url e id appartamento associato
        if(array_key_exists('imgDel', $data)){

            foreach ($data['imgDel'] as $id) {

                $img = Image::findOrFail($id);

                unlink(public_path($img -> img));

                $img -> delete();
            }
        }
        return redirect() -> route('profile', $usrid)-> with('status', 'Apartment updated successfully');
    }


    // la funzione delete cancella un appartamento. per come è impostato il database dove ci sono delle chiavi esterne si elimina tutto (onDelete('cascade')). quindi eliminando un appartamento si eliminano servizi, immagini, messaggi ecc associati all'appartamento
    public function delete($id) {

        $usrid = Auth::user() -> id;

        $apt= Apartment::findOrFail($id);

        if($apt -> user_id == $usrid){ // controllo che l'utente loggato sia effettivamente il proprietario dell'appartamento. (questo evita che uno si metta a cancellare appartamenti di altri mettendo nell'url /delete/{id a caso})

            $imgs = Image::where('apartment_id', '=', $id) -> get();
    
            // le righe dalla tabella immagini si eliminano automaticamente all'eliminazione dell'appartamento. con questo foreach elimino anche i file
            foreach ($imgs as $img) {
                unlink(public_path($img -> img));
            }
    
            $apt-> delete();
            
    
            return redirect() -> route('profile', $usrid)-> with('status', 'Apartment deleted successfully');

        } else {

            return redirect() -> route('profile', $usrid)-> with('status', 'Deleted Impossible');
        }

    }



    // la profile restituisce una view da cui l'utente può gestire tutti i suoi appartamenti.
    public function profile($id) {

        $apts= Apartment::where('user_id', '=', $id ) -> get();

        // si cicla su tutti gli appartamenti dell'utente per aggiungergli il dato sulla sponsorship che scade per ultima: se è già scaduta ed eventualmente quando scadrà
        foreach ($apts as $apt) {

            $endDate = $apt -> sponsorships -> max('end_date');


            if($endDate > date("Y-m-d h:i:sa")){ // se la data di scadenza è maggiore di adesso date("Y-m-d h:i:sa") è php che restituisce un timestamp di adesso. in laravel ci sarebbe anche Carbon::now()

                $apt['sponsored'] = Carbon::parse($endDate) -> format("d-m-Y H:i");
            } else {

                $apt['sponsored'] = false;
            }
        }

        return view('profile', compact('apts'));
    }



    // questa funzione restituisce una view con i messaggi dell'utente.
    public function messages($id) {

        // i messaggi sono associati all'appartamento quindi la query al database si compone di diversi passaggi. dai messaggi si fa una join sulla tabella appartamenti poi si prendono solo gli appartamenti di quell'utente
        $msgs= Message::join('apartments', 'messages.apartment_id', '=', 'apartments.id')
                        -> where('apartments.user_id', '=', $id) 
                        -> select('messages.*', 'apartments.title')
                        -> orderByDesc('created_at')
                        -> get();

        return view('messages', compact('msgs'));
    }


    // questa funzione restituisce una view in cui è possibile acquistare sponsorizzazioni per il proprio appartamento.
    public function promotion($id) {

        // si mandano come dati le promozioni possibili (in una tabella sono conservati prezzi e durate delle promozioni)
        $promos = Promotion::all();

        $apt = Apartment::findOrFail($id);

        // l'eventuale promozione ancora attiva e la data di scadenza. Se c'è una promozione attiva quelle acquistate andranno a prolungarla altrimenti la data di inizio della promozione da acquistare è adesso (Carbon::now())
        $endDate = $apt -> sponsorships -> max('end_date');

        if ($endDate > Carbon::now()) {

            $apt['sponsored'] = true;
            $startDate = $endDate;
        } else {

            $apt['sponsored'] = false;
            $startDate = Carbon::now();
        }

        return view ('sponsorship', compact('promos','apt', 'startDate'));
    }


    // questa funzione restituisce una pagina con le statistiche dell'appartamento. I dati dell'appartamento verrano richiesti con un ajax che punterà all'ApiController. qui si manda solo l'id dell'appartamento che l'ajax userà per sapere di quale appartamento richiedere i dati
    public function stats($id) {

        return view('stats', compact('id'));
    }

    // questa funzione serve a settare tutti i messaggi di un utente come letti. Quando un utente apre la sua pagina messaggi con un ajax si chiama questa funzione che segna tutti i messaggi come letti.
    //NOTE: per una questione logica forse questa funzione era meglio metterla nel ApiController
    public function allRead(){

        $usrId = Auth::user() -> id;

        $apts = Apartment::where('user_id', '=', $usrId) -> get();

        $aptsId = [];

        foreach ($apts as $apt) {

            $aptsId[] = $apt -> id;
        }

        // quere in permette di fare una query passandogli un array di id invece che ciclare e passargli un id alla volta. trovati i messaggi degli appartamenti di quell'utente si fa un update del dato read settando tutti su 1 cioè true (letti)
        $msgs = Message::whereIn('apartment_id', $aptsId) -> update(['read' => 1]);


        return response() -> json(['success' => 'success']);
    }
}
