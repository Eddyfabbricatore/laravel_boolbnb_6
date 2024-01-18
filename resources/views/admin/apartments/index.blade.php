@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session("success") }}
</div>
@endif

<section id="index" class="w-100 row p-5">

    @forelse ($apartments as $apartment)
        <a class=" text-decoration-none card w-100 h-50 px-0 overflow-hidden" href="{{route('admin.apartments.show',$apartment)}}">
            <div class="h-100 w-100 d-flex">
                <div class="h-100 g-0">
                    <div class="box-img h-100">
                        @if (file_exists(public_path('storage/' . $apartment->image)))
                            <img src="{{ asset('storage/' . $apartment->image) }}" class="w-100 h-100" alt="{{ $apartment->title }}">
                        @else
                            <img src="/img/{{$apartment->image}}" class="w-100 h-100" alt="{{ $apartment->title }}">
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h3 class="card-title fs-1 text-center">{{$apartment->title}}</h3>
                        <p class="card-text">Indirizzo di casa: {{$apartment->address}}</p>
                    </div>
                    <div class="icon">
                        @if($apartment->visible)
                            <i class="fa-solid fa-eye"></i>
                        @else
                            <i class="fa-solid fa-eye-slash"></i>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        </a>
    @empty
        <p>Non ci sono appartamenti</p>
    @endforelse


</section>



@endsection
