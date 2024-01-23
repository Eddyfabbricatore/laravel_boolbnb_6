@extends('layouts.app')

@section('content')
<div>
    <h1 class="text-center my-3">Scegli la tua offerta</h1>

    @if(session('errors'))
        <div style="color: red;">
            {{ session('errors') }}
        </div>
    @endif

    <div id="sponsorshipCards">
        @foreach ($sponsors as $sponsor)
            <button class="card sponsorship-card" data-price="{{ $sponsor->price }}">
                <h2>Tipo offerta {{ $sponsor->name }}</h2>
                <h3>Prezzo offerta {{ $sponsor->price }}</h3>
            </button>
        @endforeach
    </div>

    @isset($sponsor)
    <div id="Mycontainer" class="d-none">
        <form method="POST" action="{{ route('admin.payment.processPayment') }}" id="paymentForm">
            @csrf
            <p>Casa selezionata: {{ $apartment->title }}</p>
            <label for="amount">Prezzo:</label>
            <input type="text" id="amount" name="amount" readonly>



            <div id="dropin-container"></div>

            <input id="selectedSponsorship" name="selectedSponsorship" type="hidden" value="">
            <input id="nonce" name="payment_method_nonce" type="hidden" value="fake-valid-nonce">

            <button type="button" id="submitPayment" disabled>Submit Payment</button>
        </form>
    </div>
    @endisset

</div>

<script src="https://js.braintreegateway.com/web/dropin/1.41.0/js/dropin.min.js"></script>

<script>
    let form = document.getElementById('paymentForm');
    let client_token = "<?php echo $clientToken; ?>";
    let submitPaymentButton = document.getElementById('submitPayment');
    let sponsorshipCardsContainer = document.getElementById('sponsorshipCards');
    let amountInput = document.getElementById('amount');
    let dropinContainer = document.getElementById('Mycontainer');

    let instance;

    sponsorshipCardEventListener();

    function sponsorshipCardEventListener() {
        let sponsorshipCards = document.querySelectorAll('.sponsorship-card');

        sponsorshipCards.forEach(function (card) {
            card.addEventListener('click', function () {
                let selectedAmount = card.getAttribute('data-price');

                amountInput.value = selectedAmount;

                let selectedSponsorshipInput = document.getElementById('selectedSponsorship');
                selectedSponsorshipInput.value = selectedAmount;

                submitPaymentButton.removeAttribute('disabled');

                dropinContainer.classList.remove('d-none');
                sponsorshipCardsContainer.classList.add('d-none');

                createBraintreeDropIn();
            });
        });
    }

    function createBraintreeDropIn() {
        braintree.dropin.create({
            authorization: client_token,
            container: '#dropin-container'
        }, function (createErr, dropinInstance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }

            instance = dropinInstance;
        });
    }

    submitPaymentButton.addEventListener('click', function () {
        // Request the payment method nonce
        instance.requestPaymentMethod(function (err, payload) {
            if (err) {
                console.log('Request Payment Method Error', err);
                return;
            }

            // Set the value of the existing hidden input
            document.querySelector('#nonce').value = payload.nonce;

            // Continue with form submission
            form.submit();
        });
    });
</script>


@endsection
