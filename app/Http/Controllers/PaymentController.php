<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Sponsor;
use Braintree\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        //Controllo navigazione appartamenti altrui
        if (auth()->user()->id != $apartment->user_id) {
            abort(404, 'Not Found');
        }

        //Prendo tutti i sponsor
        $sponsors = Sponsor::all();

        //Genero token per Braintree authorization
        $clientToken = $this->gateway->clientToken()->generate();

        return view('payment.index', compact('clientToken' , 'apartment', 'sponsors'));
    }

    public function processPayment(Request $request, Apartment $apartment)
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
            //Impacchetto il rusultato della transazione
            $transaction = $result->transaction;

            //dump($apartment->sponsors());

            //Mi prendo l'amount per trovare lo sponsor relativo
            $selectedSponsorshipAmount = $request->input('amount');
            $sponsor = Sponsor::where('price', $selectedSponsorshipAmount)->first();

            //Aggiungo nella tabella pivot la data di transazione
            $apartment->sponsors()->attach($sponsor, [
                'transaction_date' => now(),
            ]);

            // Data di inizio della sponsorizzazione
            $dataInizio = Carbon::parse($transaction->createdAt->format('Y-m-d H:i:s'));

            // Mi prendo la durata in ore dello sponsor per effettuare i calcoli
            // $sponsor_duration = $sponsor->duration_in_hours;
            $sponsor_duration = $sponsor->duration_in_hours;

            // Calcola la data di scadenza della sponsorizzazione
            $dataScadenza = $dataInizio->copy()->addSeconds($sponsor_duration);

            // Verifica se la sponsorizzazione è ancora attiva
            if (Carbon::now()->lt($dataScadenza)) {
                // La sponsorizzazione è ancora attiva
                $tempoRimanente = max(0, $dataScadenza->diffInSeconds(Carbon::now()));
                $isSponsored = "La sponsorizzazione è attiva. Tempo rimanente: $tempoRimanente";
            } else {
                // La sponsorizzazione è scaduta
                $isSponsored = "La sponsorizzazione è scaduta.";
            }

            return view('payment.transaction', compact('transaction', 'apartment', 'sponsor_duration', 'tempoRimanente', 'isSponsored'));

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
