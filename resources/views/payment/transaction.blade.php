@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @elseif(session('errors'))
        <div style="color: red;">
            {{ session('errors') }}
        </div>
    @endif
    <div class="container">
        <h1>Payment Result</h1>

        @isset($transaction)
        <div class="card">
            <h2>Riepilogo transazione</h2>

            <!-- <div style="
    background-image: url('@if(file_exists(public_path('storage/' . $apartment->image) )){{ asset('storage/' . $apartment->image) }}@else/img/{{$apartment->image}}@endif');
    background-size: cover;"> -->
            <div style="background-image: url(public_path('storage/' . $apartment->image));">
                <p>Hai sponsorizzato: <span id="sponsored">{{ $apartment->title }}</span> con l'abbonamento {{$sponsor->name}}</p>
                <p>Status: {{ $transaction }}</p>
                <p>Data di transizione: {{ $transaction->createdAt->format('Y-m-d H:i:s') }}</p>
                <p>{{ $isSponsored }}</p>
                <p>Durata sponsorizzazione in ore: {{ $sponsor_duration }}</p>
                <!-- <p id="tempo-rimanente">Rimangono {{ $newEndDate }} secondi alla fine della sponsorizzazione</p> -->
            </div>
        </div>
        <div class="mt-3">
            <a class="btn btn-secondary" href="{{route('admin.apartments.show', $apartment)}}">Torna all'appartamento</a>
        </div>
        @endisset
    </div>

    <script>
        setInterval(function() {
            let tempoRimanente = parseInt(document.getElementById('tempo-rimanente').innerText);
            let sponsored = document.getElementById('sponsored');

            if (tempoRimanente > 0) {
                tempoRimanente--;
                document.getElementById('tempo-rimanente').innerText = tempoRimanente;
            } else if (tempoRimanente === 0) {
                sponsored.innerText = sponsoredApartment = 'no';
            }
        }, 1000);
    </script>
@endsection
