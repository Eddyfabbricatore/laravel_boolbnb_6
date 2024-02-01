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
    <div class="container h-100 position-relative">
        <a class="position-fixed z-2 top-0 start-50 translate-middle pt-5 my-2 text-light " href="{{route('admin.apartments.show',$apartment->slug)}}">
            <p class="btn mt-4 w-100 h-100 fs-5 btn-outline-light">Torna all'appartamento</p>
        </a>
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
        @endisset
    </div>

@endsection
