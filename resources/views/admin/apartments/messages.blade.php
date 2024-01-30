@extends('layouts.app')

@section('content')

    <section class="container w-100 h-100 py-3">
        <h1 class="text-black">I tuoi messaggi</h1>

        <div id="show-form" class="card p-5">
            <div class="d-flex flex-wrap gap-5 align-items-center ">

                @foreach ($messages as $message)
                    <div class="list-group">
                        <a class="list-group-item">
                            <div class="w-100">
                                <h5 class="mb-1">{{ $message->full_name }}</h5>
                                <small class="text-secondary">{{ \Carbon\Carbon::parse($message->date)->locale('it')->isoFormat('DD MMMM YYYY') }}</small>
                            </div>
                            <p class="mb-1"><em>{{ $message->email }}</em></p>
                            <small>{{ $message->message }}</small>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
