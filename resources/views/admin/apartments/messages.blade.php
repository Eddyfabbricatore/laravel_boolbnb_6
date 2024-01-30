@extends('layouts.app')

@section('content')

    <section class="container w-100 h-100 py-3">
        <h1 class="text-black">I tuoi messaggi</h1>

        <div id="show-form" class="card p-5">

            @if (count($messages) == 0)
                <h5>Non hai ancora ricevuto messaggi per questo appartamento</h5>
            @else
                @foreach ($messages as $message)
                    <div class="media-body u-shadow-v18 g-pa-30 mb-2 w-75">
                        <div class="g-mb-15">
                            <h5 class="h5 g-color-gray-dark-v1 mb-0">{{ $message->full_name }}</h5>
                            <span class="g-color-gray-dark-v4 g-font-size-12">{{ \Carbon\Carbon::parse($message->date)->locale('it')->isoFormat('DD MMMM YYYY') }}</span>
                        </div>
                        <p>{{ $message->message }}</p>
                        <ul class="list-inline d-sm-flex my-0">
                            <li class="list-inline-item">
                                <a class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="mailto:{{ $message->email }}">
                                    <i class="fa fa-reply g-pos-rel g-top-1 g-mr-3"></i>
                                    Reply
                                </a>
                            </li>
                        </ul>
                    </div>
                @endforeach
            @endif


        </div>
    </section>
@endsection
