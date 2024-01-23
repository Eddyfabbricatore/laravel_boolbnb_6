<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        // Genero il client token
        $clientToken = $this->gateway->clientToken()->generate();

        return view('payment.index', compact('clientToken'));
    }

    public function processPayment(Request $request)
    {
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
            return view('payment.transaction', compact('transaction'));
        } else {
            $errorString = "";
            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            session()->flash('errors', $errorString);
            return redirect()->route('admin.payment.processPayment');
        }
    }
}
