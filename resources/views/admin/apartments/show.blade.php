@extends('layouts.app')

@section('content')

    <section class="container w-100 h-100 d-flex justify-content-center align-items-center" style="
        background-image: url('@if(file_exists(public_path('storage/' . $apartment->image) )){{ asset('storage/' . $apartment->image) }}@else/img/{{$apartment->image}}@endif'); background-size: cover;">
        <a class="position-fixed z-2 top-0 start-50 translate-middle pt-5 my-2 text-light " href="javascript:history.go(-1)">
            <p class="btn mt-4 w-100 h-100 fs-5 btn-outline-light">Torna indietro</p>
        </a>

        <div id="show-form" class="card p-5">
            <div class="d-flex gap-5 align-items-center ">
                <h1 class="text-dark ">{{$apartment->title}}</h1>
                <div class="d-flex gap-2">

                    <a class="nav-link btn btn-warning" href="{{route('admin.apartments.edit', $apartment->slug)}}">
                        <button class="btn btn-warning"><i class="fa-sharp fa-solid fa-pen"></i></button>
                    </a>
                    <a class="nav-link btn btn-warning" href="{{route('admin.messages',$apartment->slug)}}">
                        <button class="btn btn-secondary h-100 w-100">
                            <i class="fa-solid fa-message"></i>
                        </button>
                    </a>
                    <form action={{route("admin.apartments.destroy", $apartment->slug)}} method="post" onsubmit="return confirm('Are you sure you want to delete this apartment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                    <a class="nav-link" href="{{route('admin.payment', $apartment->slug)}}">
                        <button class="btn btn-success"><i class="fa-solid fa-credit-card"></i></button>
                    </a>

                    <a class="nav-link" href="{{route('admin.stats', $apartment->slug)}}">
                        <button class="btn btn-success"><i class="fa-solid fa-chart-line"></i></button>
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
