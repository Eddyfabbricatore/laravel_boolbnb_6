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
            'environment' => getenv('BT_ENVIRONMENT'),
            'merchantId' => getenv('BT_MERCHANT_ID'),
            'publicKey' => getenv('BT_PUBLIC_KEY'),
            'privateKey' => getenv('BT_PRIVATE_KEY'),
        ]);
    }

    public function generateCreditCardToken()
    {
        // Sostituisci 'your_customer_id' con l'ID del cliente effettivo se applicabile
        $customerId = 'your_customer_id';

        // Esegui la creazione della carta di credito
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
        // Genera il client token
        $clientToken = $this->gateway->clientToken()->generate();

        // dd($clientToken);

        // Genera il token della carta di credito
        $creditCardToken = $this->generateCreditCardToken();

        // dd($creditCardToken);

        // Passa i token alla vista 'payment.index'
        return view('payment.index', compact('clientToken', 'creditCardToken'));
    }

    public function processPayment(Request $request, Gateway $gateway)
    {
        // Ottieni l'importo e il nonce dalla richiesta POST
        $amount = $request->input('amount');
        $nonce = $request->input('payment_method_nonce');

        // Esegui la transazione con Braintree
        $result = $this->gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true,
            ],
        ]);

        // Gestisci il risultato della transazione
        if ($result->success || !is_null($result->transaction)) {
            $transaction = $result->transaction;
            return redirect()->route('admin.payment.transaction', ['id' => $transaction->id]);
        } else {
            $errorString = "";

            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }

            session()->flash('errors', $errorString);
            return view('payment.index');
        }
    }
}
