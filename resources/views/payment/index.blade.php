@extends('layouts.app')

@section('content')
<div id="index_payment">
    @if(session('errors'))
    <div style="color: red;">
        {{ session('errors') }}
    </div>
    @endif


    <h1 id="sponsor_title" class="text-center m-0">Scegli la tua offerta</h1>
    <p id="sponsor_paragraph" class="mt-2 text-center alert alert-dark w-50 m-auto my-0" role="alert">NB! Se hai gia una sponsorizzazione attiva il tempo di sponsorizzazione verrà sommato alla scadenza gia prefissata.</p>

    <div id="sponsor_cards" class="w-75 d-flex flex-column justify-content-evenly m-auto my-0 flex-md-row">
        @foreach ($sponsors as $sponsor)
            <div id="card_{{$sponsor->id}}" class="btn d-flex flex-column justify-content-evenly flex-grow-1 flex-md-grow-0 sponsorship-card my-1" data-price="{{ $sponsor->price }}">
                <h1>{{ $sponsor->name }}</h1>
                <h3>Prezzo: € {{ $sponsor->price }}</h3>
                <h3>Durata: {{ $sponsor->duration_in_hours }}h</h3>
                <p class="h-25 w-75 m-auto my-0">La sponsorizzazione {{ $sponsor->name }} ti da la possibilità di metterre in evidenza {{ $apartment->title }} per la durata di {{ $sponsor->duration_in_hours }}h. <br>Questo aumenta la possibilita che tu riesca ad affittarlo!!</p>
            </div>
        @endforeach
    </div>

    @isset($sponsor)
    <div id="box_form" class="d-none h-100">
        <form method="POST" action="{{ route('admin.payment.processPayment', $apartment) }}" id="payment_form" class="card d-flex flex-column justify-content-evenly w-50 m-auto h-100">
            @csrf
            <div class="payment-description d-flex flex-column justify-content-evenly gap-5 h-25">
                <h1 class="text-center mb-4 h-50">Hai selezionato l'offerta {{$sponsor->name}} per <br> {{ $apartment->title }}</h1>
                <h2 class="text-center">Il prezzo dell'offerta selezionata è di {{$sponsor->price}}€</h2>
            </div>

            <div class="payment-box h-50 m-auto my-0">
                <div class="drop-in-box d-flex flex-column align-items-center">
                    <div id="dropin_container"></div>
                    <button class="btn btn-outline-dark mt-4 m-auto" type="button" id="submit_payment" disabled>Submit Payment</button>
                </div>

                <label class="d-none" for="amount"></label>
                <input class="d-none" id="amount" type="text"  name="amount" readonly>
                <input class="d-none"id="apartment_id" name="apartment_id" type="text" value="{{ $apartment->id }}" >
                <input type="hidden" id="selected_sponsor_input" name="selected_sponsor_input">
                <input type="hidden" id="nonce" name="payment_method_nonce" value="fake-valid-nonce">

            </div>
        </form>
    </div>
    @endisset

</div>

<script src="https://js.braintreegateway.com/web/dropin/1.41.0/js/dropin.min.js"></script>

<script>
    //Sponsor
    let sponsorTitle = document.getElementById('sponsor_title');
    let sponsorParagraph = document.getElementById('sponsor_paragraph');
    let sponsorCards = document.getElementById('sponsor_cards');
    let selectedSponsorInput = document.getElementById('selected_sponsor_input');
    //Token
    let clientToken = "<?php echo $clientToken; ?>";
    //Form container
    let dropinContainer = document.getElementById('box_form');
    let amountInput = document.getElementById('amount');
    //Form submit
    let submitPaymentButton = document.getElementById('submit_payment');
    let form = document.getElementById('payment_form');
    //Gen declaration
    let instance;

    //Faccio partire la funzione
    sponsorshipCardEventListener();

    function sponsorshipCardEventListener() {
        let sponsorshipCards = document.querySelectorAll('.sponsorship-card');
        sponsorshipCards.forEach(function (card) {
            card.addEventListener('click', function () {
                //Prendo i valori dell' amount
                let selectedAmount = card.getAttribute('data-price');
                amountInput.value = selectedAmount;

                selectedSponsorInput.value = selectedAmount;
                //Abilito al pagamento
                submitPaymentButton.removeAttribute('disabled');

                //Nascondo gli sponsor e mostro il dropin
                dropinContainer.classList.remove('d-none');
                sponsorTitle.classList.add('d-none');
                sponsorParagraph.classList.add('d-none');
                sponsorCards.classList.add('d-none');

                //Genero il drop-in
                createBraintreeDropIn();
            });
        });
    }
    //Genero il dropin di pagamento
    function createBraintreeDropIn() {
        braintree.dropin.create({
            authorization: clientToken,
            container: '#dropin_container'
        }, function (createErr, dropinInstance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }

            instance = dropinInstance;
        });
    }

    submitPaymentButton.addEventListener('click', function () {
        // Richiesta payment method nonce
        instance.requestPaymentMethod(function (err, payload) {
            if (err) {
                console.log('Request Payment Method Error', err);
                return;
            }
            // Prendo il valore dall'input esistente hidden
            document.querySelector('#nonce').value = payload.nonce;

            form.submit();
        });
    });
</script>

@endsection
