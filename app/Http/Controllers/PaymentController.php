<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Sponsor;
use Braintree\Gateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $gateway;  // Aggiungi questa dichiarazione

    public function __construct()
    {
        // Inizializza l'istanza di Braintree Gateway
        $this->gateway = new Gateway([
            'environment' => env('BT_ENVIRONMENT'),
            'merchantId' => env('BT_MERCHANT_ID'),
            'publicKey' => env('BT_PUBLIC_KEY'),
            'privateKey' => env('BT_PRIVATE_KEY'),
        ]);
    }

    public function generateCreditCardToken()
    {
        $customerId = 'your_customer_id';

        // Creazione della carta di credito
        $result = $this->gateway->creditCard()->create([
            'customerId' => $customerId,
            'number' => '4111111111111111',
            'expirationDate' => '12/24',
            'cvv' => '123',
        ]);

        if ($result->success) {
            $creditCardToken = $result->creditCard->token;
            // Puoi fare qualcosa con il token, ad esempio salvarlo nel database
            // o utilizzarlo per una transazione successiva
            echo 'Credit card generated successfully. Token: ' . $creditCardToken;
        } else {
            echo 'Error generating credit card: ' . $result->message;
        }
    }

    public function index(Apartment $apartment)
    {
        session_start();
        $apartment = Apartment::where('id', $apartment->id)->first();
        dump($apartment);
        $sponsors = Sponsor::all();
        $clientToken = $this->gateway->clientToken()->generate();
        $_SESSION['apartment_id'] = $apartment->id;

        return view('payment.index', compact('clientToken' , 'apartment', 'sponsors'));
    }

    public function processPayment(Request $request, Apartment $apartment)
    {
        dump($request->input('apartment_id'));
        dump($apartment->sponsors());
        $apartment = Apartment::where('id', $request->id)->first();
        //Prendo i valori in entrata
        $amount = $request->input('amount');
        $nonce = $request->input('payment_method_nonce');

        // Eseguo la transazione con Braintree
        $result = $this->gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true,
            ],
        ]);

        // Gestisco il risultato della transazione
        if ($result->success || !is_null($result->transaction)) {


            $transaction = $result->transaction;

            /* $selectedSponsorshipAmount = $request->input('selectedSponsorship');

            // Find the sponsor based on the selected amount
            $sponsor = Sponsor::where('price', $selectedSponsorshipAmount)->first();

            // Attach the sponsorship with additional data to the pivot table
            $apartment->sponsors()->attach($sponsor, [
                'transaction_date' => now(),
            ]); */
            return view('payment.transaction', compact('transaction', 'apartment'));
        } else {
            $errorString = "";
            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            session()->flash('errors', $errorString);
            return redirect()->route('admin.payment.processPayment', compact('apartment'));
        }
    }
}
