@extends('layouts.app')

@section('content')

    <section class="container w-100 h-100 py-3">
        <a class="position-fixed z-2 top-0 start-50 translate-middle pt-5 my-2 text-light " href="javascript:history.go(-1)">
            <p class="btn mt-4 w-100 h-100 fs-5 btn-outline-light">Torna indietro</p>
        </a>
        <h1 class="text-black">I tuoi messaggi</h1>

        <div id="show-form" class="card p-5">

            @if (count($messages) == 0)
                <h5>Non hai ancora ricevuto messaggi per questo appartamento</h5>
            @else


            {{$messages->links()}}
            <div class="container">
                @foreach ($messages as $message)
                <div class="media-body u-shadow-v18 g-pa-30 mb-2 text-center mx-auto">
                    <div class="g-mb-15">
                        <h5 class="h5 g-color-gray-dark-v1 mb-2 mb-0">{{ $message->full_name }}</h5>
                        <span class="g-color-gray-dark-v4 g-font-size-12">{{ \Carbon\Carbon::parse($message->date)->locale('it')->isoFormat('DD MMMM YYYY') }}</span>
                    </div>
                    <p class="mt-4">{{ $message->message }}</p>
                    <ul class="list-inline d-sm-flex my-0 float-end">
                        <li class="list-inline-item">
                            <a class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="mailto:{{ $message->email }}">
                                <i class="fa fa-reply g-pos-rel g-top-1 g-mr-3"></i>
                                Reply
                            </a>
                        </li>
                    </ul>
                </div>
            @endforeach
            </div>
            {{$messages->links()}}



            @endif


        </div>
    </section>
@endsection
