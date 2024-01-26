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

            //Mi prendo l'amount per trovare lo sponsor relativo
            $selectedSponsorshipAmount = $request->input('amount');
            $sponsor = Sponsor::where('price', $selectedSponsorshipAmount)->first();

            // Data di inizio della sponsorizzazione
            $dataInizio = Carbon::parse($transaction->createdAt->format('Y-m-d H:i:s'));

            // Mi prendo la durata in ore dello sponsor per effettuare i calcoli
            $sponsor_duration = $sponsor->duration_in_hours;

            // Calcolo la data di scadenza della sponsorizzazione
            $dataScadenza = $dataInizio->copy()->addHours($sponsor_duration);

            //Controllo se esite una sponsorizzazione attiva
            $existingSponsorship = $apartment->sponsors()
                ->where('end_sponsor_date', '>', Carbon::now())
                ->first();

            if ($existingSponsorship) {
                $newEndDate = Carbon::parse($existingSponsorship->pivot->end_sponsor_date)->addHours($sponsor_duration);
                $apartment->sponsors()->attach($sponsor, [
                    'end_sponsor_date' => $newEndDate,
                    'transaction_date' => Carbon::parse($transaction->createdAt->format('Y-m-d H:i:s')),
                ]);
            } else {
                $apartment->sponsors()->attach($sponsor, [
                    'transaction_date' => Carbon::parse($transaction->createdAt->format('Y-m-d H:i:s')),
                    'end_sponsor_date' => $dataScadenza,
                ]);
            }

            // Verifica se la sponsorizzazione è ancora attiva
            $apartment->setAttribute('sponsorizzato', true);
            $apartment->setAttribute('tempo_rimanente', $newEndDate);
            $isSponsored = "La sponsorizzazione è attiva. Data di scadenza: $newEndDate";

            return view('payment.transaction', compact('transaction', 'apartment', 'sponsor_duration', 'newEndDate', 'isSponsored'));

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
