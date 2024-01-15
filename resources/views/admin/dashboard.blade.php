@extends('layouts.app')

@section('content')

    <div class="container">
        <ul>
            <li><a href="{{route('admin.apartments.create')}}">Crea un appartamento</a></li>
            <li><a href="{{route('admin.apartments.index')}}">Vai agli appartamenti</a></li>
        </ul>
    </div>

    {{-- <ul>
        @foreach ($apartments as $apartment)
            <li>
                {{$apartment->title}}
                @forelse ($apartment->services as $service)
                    <span class="badge text-bg-info">{{ $service->name }}</span>
                @empty
                    -
                @endforelse
            </li>
        @endforeach
    </ul> --}}

@endsection
