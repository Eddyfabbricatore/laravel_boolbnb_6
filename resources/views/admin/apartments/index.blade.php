@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session("success") }}
</div>
@endif

<section class="container row">

    @forelse ($apartments as $apartment)
        <a class=" text-decoration-none " href="{{route('admin.apartments.show',$apartment)}}">
            <div class="w-100 col-6 card mb-3 m-auto gap-4">
                <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{asset('storage/'.$apartment->image)}}" onerror="this.src='/img/Placeholder.png'" class="card-img-top" alt="{{$apartment->title}}">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title fs-1 text-center">{{$apartment->title}}</h5>
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
        <p>non ci sono appartamenti</p>
    @endforelse


</section>



@endsection
