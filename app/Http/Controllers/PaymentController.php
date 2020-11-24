<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sponsorship;
use App\Apartment;
use Carbon\Carbon;
use Braintree\Gateway;
use App\Promotion;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    // questo controller si occupa della gestione dei pagamenti attraverso Braintree.
    // Braintree lavora in due fasi. crea un form in cui prede e verifica il metodo di pagamento inserito dall'utente (nel nostro caso è possibile pagare solo con carta di credito.) in un primo momento verifica siano effettivamente i numeri di una carta di credito e questo è gestito in automatico nel frontend.
    // in un secondo momento addebita sulla carta il prezzo e questa parte viene gestita in questo controller (per ora ovviamente è tutta una simulazione)
    // carte di test: valida : 4111 1111 1111 1111  -- non valida: 4111 4111 4111 4111111
    public function sponsorshipPayment(Request $request, $id){
    
        // il gateway è un oggetto di braintree nella construct prende dall'env i dati del mio account su braintree
        $gateway = new Gateway([
        'environment' => env('BRAINTREE_ENV'),
        'merchantId' => env('BRAINTREE_MERCHANT_ID'),
        'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
        'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        // dal form prendiamo quale promotion è stata selezionata. Da quello prendiamo il costo e la durata della promozione.
        $promoId = $request['promo_id'];

        $promo = Promotion::where('id', '=', $promoId) -> first();

        $price = $promo['price'];

        $hours = $promo['hours'];

        // nel form prendiamo anche i dati (creati da braintree) del metodo di pagamento (in questo caso saranno tutti i dati della carta di credito inserita)
        $nonce = $request->payment_method_nonce;


        // attraverso gateway -> transaction -> sale implementiamo il pagamento. gli passiamo il costo da addebitare preso dalla promotion selezionata e i dati del metodo di pagamento $nonce
        $result = $gateway->transaction()->sale([
            'amount' => $price,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        // Nella variabile result troviamo il risultato della transazione.
        if ($result->success || !is_null($result->transaction)) {
            
            // se è andato a buon fine prendiamo dal form la data di inizio della promozione (che può essere adesso o eventualmente la data di fine della promozione precedente ancora attiva). Ci aggiungo le ore per cui l'utente ha pagato prese dalla promozione selezionata
            $endDate = Carbon::parse($request['start_date']) -> addHours($hours);

            $startDate = $request['start_date'] ;

            Sponsorship::create([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'apartment_id' => $id,
                'promotion_id' => $promoId
            ]);


            $usrId = Auth::user() -> id;

            // restitutisco la view della pagina profilo con il messaggio di andata a buon fine dell'operazione
            return redirect() -> route('profile', $usrId)-> with('status', 'Sponsorship created successfully');

      
        }else {

            // se la transazione non va a buon fine nella variabile errors creo una stringa con tutti i messaggi di errore contenuti in $result
            $errors = "";

            foreach($result->errors->deepAll() as $error) {
                $errors .= 'Error: ' . $error->code . ": " . $error->message . "<br>";
            }

            // con return back restituisco la pagina di sponsorizzazione dell'appartamento con il messaggio di errore
            return back()-> with('error', $errors);
        }
    }
}
