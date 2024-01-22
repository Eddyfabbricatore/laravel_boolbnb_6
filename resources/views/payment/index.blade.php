@extends('layouts.app')

@section('content')
<div>
    <h1>Payment Form</h1>

    @if(session('errors'))
        <div style="color: red;">
            {{ session('errors') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.payment.processPayment') }}">
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" placeholder="Enter the amount">

        <div id="dropin-container"></div>

        <input id="nonce" name="payment_method_nonce" type="hidden" />

        <button type="submit">Submit Payment</button>
    </form>
</div>

<script src="https://js.braintreegateway.com/web/dropin/1.41.0/js/dropin.min.js"></script>
<script>
    let form = document.querySelector('form');
    let client_token = "{{$clientToken}}";

    braintree.dropin.create({
        authorization: client_token,
        container: '#dropin-container'
    }, function (createErr, instance) {
        if (createErr) {
            console.log('Create Error', createErr);
            return;
        }

        // Move the event listener outside the dropin.create callback
        form.addEventListener('submit', function (event) {
            // Prevent the form from submitting immediately
            event.preventDefault();

            // Request the payment method nonce
            instance.requestPaymentMethod(function (err, payload) {
                if (err) {
                    console.log('Request Payment Method Error', err);
                    return;
                }

                // Set the value of the existing hidden input
                document.querySelector('[name="payment_method_nonce"]').value = payload.nonce;

                // Continue with form submission
                form.submit();
            });
        });
    });
</script>

@endsection