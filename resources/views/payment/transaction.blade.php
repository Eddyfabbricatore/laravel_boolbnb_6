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
    <div class="container h-100">
        @isset($transaction)
        <div class="card mt-3">
            <h1>Riepilogo transazione</h1>


            <div style="
                background-image: url('@if(file_exists(public_path('storage/' . $apartment->image) )){{ asset('storage/' . $apartment->image) }}@else/img/{{$apartment->image}}@endif');
                background-size: cover;
                background-position: center;"
                 class="d-flex">
                <div class="glass-form w-50 flex-grow-1 flex-sm-grow-0 d-flex flex-column justify-content-evenly">
                    <h2 class="text-center">Hai sponsorizzato <br><span id="sponsored"><strong>{{ $apartment->title }}</strong></span> con l'abbonamento <strong>{{$sponsor->name}}</strong></h2>
                    <h4 class="text-center">Data di transizione: {{ $transaction->createdAt->format('Y-m-d H:i:s') }}</h4>
                    <h4 class="text-center">{{ $isSponsored }}</h4>
                    <p class="text-center">Durata sponsorizzazione in ore: {{ $sponsor_duration }}</p>
                    {{-- <p>Rimangono <span id="tempo_rimanente"></span> secondi alla fine della sponsorizzazione</p> --}}
                </div>
            </div>

        </div>
        <div class="mt-3 d-flex justify-content-center">
            <a class="btn btn-secondary" href="{{route('admin.apartments.show', $apartment->slug)}}">Torna all'appartamento</a>
        </div>
        @endisset
    </div>

    <script>
        setInterval(function() {
            let tempoRimanente = parseInt(document.getElementById('tempo_rimanente').innerText);
            let sponsored = document.getElementById('sponsored');

            if (tempoRimanente > 0) {
                tempoRimanente--;
                document.getElementById('tempo_rimanente').innerText = tempoRimanente;
            } else if (tempoRimanente === 0) {
                sponsored.innerText = sponsoredApartment = 'no';
            }
        }, 1000);
    </script>
@endsection
