@extends('layouts.main-layout')

@section('import')
<script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>
<script src="{{ asset('/js/partials/sponsorship.js')}} "></script> 
@endsection

@section('content')
<div class="container margintop">
    <section class="pricing pb-4">
        <div class="container">
            {{-- stampo errori se nella session viene valorizzato un attributo errore (nel controller si fa return ->with('error', 'messaggio di errore')) --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {!! session('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if ($apt -> sponsored)
                <h1 class="pb-4 text-center">This apartment is already sponsored until: {{ Carbon\Carbon::parse($startDate) -> format("d/m/Y H:i") }}</h1>
                <h1 class="pb-4 text-center">Add More Hours!</h1>
                @else
                <h1 class="pb-4 text-center">Promote your apartment</h1>
            @endif
            <div class="row pb-2">
                @foreach($promos as $promo)
                <div class="my-3 col-sm-8 offset-sm-2 offset-lg-0 col-lg-4">
                    <div class="card promo" data-price="{{$promo -> price}}" data-hours="{{$promo -> hours}}">
                        <div class="card-body">
                            <h5 class="card-title text-muted text-uppercase text-center">{{$promo -> hours}}h Users</h5>
                            <h6 class="card-price text-center">${{$promo -> price}}<span class="period"></span></h6>
                            <hr>
                            <ul class="fa-ul">
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>Always First Results</li>
                                <li><span class="fa-li"><i class="fas fa-check"></i></span>Guaranteed Visibility</li>
                            </ul>
                            <button data-id="{{ $promo -> id }}" class="btn btn-block btn-secondary text-uppercase choose">Choose</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row pt-5">
                {{-- questo contenitore contiene la stampa grafica dei dettagli dell'ordine e il form per inserire i dettagli della carta di credito. viene mostrato solo alla selezione di una delle promozioni --}}
                <div class="col-md-8 offset-md-2 d-none" id="order-container">
                    <div>
                        <h4>Order Details</h4>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                  <th scope="row">Price:</th>
                                  <td><span id="details-price"></span>$</td>
                                </tr>
                                <tr>
                                  <th scope="row">Sponsorship ending date:</th>
                                  <td><span id="details-ending"></span></td>
                                </tr>
                                <tr>
                                  <th scope="row">Apartment:</th>
                                  <td>{{ $apt -> title }} </td>
                                </tr>
                              </tbody>
                        </table>
                    </div>
                    <form id="payment-form" action="{{ route('apt-sponsorship', $apt -> id) }}" method="post">
                        @csrf
                        @method("POST")
                        
                        <div id="bt-dropin"></div>
                        {{-- alla selezione di una promozione viene valorizzato il valore di questo input nascosto con l'id della promozione scelta --}}
                        <input type="number" name="promo_id" id="promo_id" class="d-none" required>
                        {{-- la data di inizio viene inviata dal controller a seconda se ci sono ancora sponsorizzazioni attive --}}
                        <input type="text" name='start_date' id="start_date" class="d-none" value="{{ $startDate }} " required>
                        {{-- questo input viene valorizzato con i dettagli della carta di credito (sono dettagli creati da Braintree) --}}
                        <input id="nonce" name="payment_method_nonce" type="hidden">
                    
                        <button class="btn btn-secondary rounded-pill" id="payment-submit-button" type="submit">
                            Submit Payment
                        </button>
                    </form>
                </div>
            </div>
    </section>
    <a class="btn btn-primary float-right" href="{{ URL::previous() }}">Go Back</a>
</div>

<script>

    // questo script crea il form di inserimento dei dati della carta di credito
    var form = document.querySelector('#payment-form'); // questo è il form completo di tutti i dati che invierà il pagamento
    var client_token = "{{ Braintree\ClientToken::generate() }}";
    
    braintree.dropin.create({
            authorization: client_token,
            selector: '#bt-dropin',
        },
        function (createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                
                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.log('Request Payment Method Error', err);
                        return;
                    }
                    // se i dati di pagamento sono validi aggiungo i dettagli del metodo di pagamento in un input nascosto del form e poi lo invio (form.submit())
                    document.querySelector('#nonce').value = payload.nonce;
                    $('#payment-submit-button').prop("disabled", true);
                    form.submit();
                });
            });
        });
</script>
@endsection