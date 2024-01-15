@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Il tuo appartamento</h1>

        <div class="card mb-3">
            <img src="{{asset('storage/'.$apartment->image)}}" onerror="this.src='/img/Placeholder.png'" class="card-img-top" alt="{{$apartment->title}}">
            <div class="card-body">
                <h5 class="card-title">{{ $apartment->title }}</h5>
                <p>Numero di stanze: {{ $apartment->rooms }} | Numero di bagni:{{ $apartment->bathrooms }} | Numero di camere da letto:{{ $apartment->beds }}</p>
                <p>Metri quadrati: {{ $apartment->square_meters }}</p>
                <p>Indirizzo: {{ $apartment->address }}</p>
                </div>

                <div>
                    @forelse($apartment->services as $service)
                        <span class="badge text-bg-dark my-3">{{ $service->name }}</span>
                    @empty
                        <span class="badge text-bg-dark my-3">Non ha servizi</span>
                    @endforelse
                </div>
                <div>
                    <a class="btn btn-secondary" href="{{route('admin.apartments.index')}}">Torna alla Lista</a>
                </div>
            </div>
        </div>
    </section>

@endsection
