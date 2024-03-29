@extends('layouts.app')

@section('content')

    {{-- @php
        session_start();

        $_SESSION["loggedUser"] = auth();
        $alfonso = auth()->user()->name;

    @endphp
    @dump($_SESSION); --}}

    <div id="dashboard" class="container h-100 d-flex align-items-center">
        <div class="route-select border rounded-3 m-auto p-5 w-100 d-flex align-items-center justify-content-center flex-column">
            <h1 class="text-center">Bentornato {{$user->name}} {{$user->surname}}!!</h1>

            <h3>Scegli cosa fare:</h3>
            <ul class=" flex-column flex-md-row w-100 d-flex my-4 px-0 gap-5 list-unstyled ">
                <li class="h-100 flex-grow-1">
                    <a href="{{route('admin.apartments.create')}}">
                        <div class="icon border rounded-5">
                            <i class="fa-solid fa-house-medical"></i>
                            <p>Aggiungi un nuovo appartamento</p>
                        </div>
                    </a>
                </li>
                <li class="h-100 flex-grow-1">
                    <a class="w-100"  href="{{route('admin.apartments.index')}}">
                        <div class="icon border rounded-5 w-100">
                            <div class="d-flex justify-content-center gap-2 w-100">
                                <i class="fa-solid fa-list"></i>
                                <i class="fa-solid fa-house"></i>
                            </div>
                            <p>Vai alla lista dei tuoi appartamenti</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- <script>

        let alfonsoJS = {!! json_encode($alfonso, JSON_HEX_TAG) !!}


        window.localStorage.setItem("name", alfonsoJS);

    </script> --}}

@endsection
