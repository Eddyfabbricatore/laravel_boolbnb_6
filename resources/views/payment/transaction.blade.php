@extends('layouts.app')

@section('content')
    <div>
        <h1>Payment Result</h1>

        @if(session('success'))
            <div style="color: green;">
                {{ session('success') }}
            </div>
        @elseif(session('errors'))
            <div style="color: red;">
                {{ session('errors') }}
            </div>
        @endif

        @isset($transaction)
        <div class="card">
            <h2>Transaction Details:</h2>
            <ul>
                <li>Amount: {{ $transaction->amount }}</li>
                <li>Status: {{ $transaction->status }}</li>
                <li>Data di transizione: {{ $transaction->createdAt->format('Y-m-d H:i:s') }}</li>
                <li>Durata sponsorizzazione in ore: {{ $sponsor_duration }}</li>
                <li>{{ $isSponsored }}</li>
                <li>Tempo rimanente: <span id="tempo-rimanente">{{ $tempoRimanente }}</span> secondi</li>
                <!-- Add more transaction details as needed -->
            </ul>
        </div>
        @endisset
    </div>

    <script>
        setInterval(function() {
        var tempoRimanente = parseInt(document.getElementById('tempo-rimanente').innerText);
        if (tempoRimanente > 0) {
            tempoRimanente--;
            document.getElementById('tempo-rimanente').innerText = tempoRimanente;
        }
    }, 1000);
    </script>
@endsection
