@extends('layouts.app')

@section('content')

    <section class="container w-100 h-100 d-flex justify-content-center align-items-center" style="
        background-image: url('@if(file_exists(public_path('storage/' . $apartment->image) )){{ asset('storage/' . $apartment->image) }}@else/img/{{$apartment->image}}@endif'); background-size: cover;">

        <div id="show-form" class="card p-5">
            <div class="d-flex gap-5 align-items-center ">
                <h1 class="text-dark ">{{$apartment->title}}</h1>
                <div class="d-flex gap-2">

                    <a class="nav-link btn btn-warning" href="{{route('admin.apartments.edit', $apartment)}}">
                        <button class="btn btn-warning"><i class="fa-sharp fa-solid fa-pen"></i></button>
                    </a>
                    <form action={{route("admin.apartments.destroy", $apartment)}} method="post" onsubmit="return confirm('Are you sure you want to delete this apartment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                    <a class="nav-link" href="{{route('admin.payment')}}">
                        <button class="btn btn-success"><i class="fa-solid fa-credit-card"></i></button>
                    </a>
                </div>
            </div>


            <div class="card-body">
                <p>Indirizzo: {{ $apartment->address }}</p>
                <p>Numero di stanze: {{ $apartment->rooms }}</p>
                <p>Metri quadrati: {{ $apartment->square_meters }}</p>
                <p>Numero di bagni:{{ $apartment->bathrooms }}</p>
                <p>Numero di camere da letto:{{ $apartment->beds }}</p>
                </div>

                <div>
                    <h5>Servizi</h5>
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
