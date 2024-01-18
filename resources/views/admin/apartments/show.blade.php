@extends('layouts.app')

@section('content')
    <style>
        section.container{
            background-image: url('{{ asset("storage/".$apartment->image) }}');
            background-size: cover;
        }
    </style>
    <section class="container w-100 h-100">

        <div class="card mb-3">
            <div class="d-flex gap-5 align-items-center ">
                <h1 class="text-dark ">Il tuo appartamento</h1>

                <form action={{route("admin.apartments.destroy", $apartment)}} method="post" onsubmit="return confirm('Are you sure you want to delete this apartment?')">
                    <button class="btn btn-warning">
                        <a class="nav-link btn btn-warning" href="{{route('admin.apartments.edit', $apartment->id)}}"><i class="fa-sharp fa-solid fa-pen"></i></a>
                    </button>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash fa-bounce"></i>
                    </button>
                </form>
            </div>

            <!-- <img src="{{asset('storage/'.$apartment->image)}}" onerror="this.src='/img/Placeholder.png'" class="card-img-top" alt="{{$apartment->title}}"> -->
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
