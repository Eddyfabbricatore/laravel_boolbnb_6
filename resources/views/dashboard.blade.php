@extends('layouts.app')

@section('content')

    <ul>
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
    </ul>

@endsection
