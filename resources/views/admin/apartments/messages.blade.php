@extends('layouts.app')

@section('content')

    <section class="container w-100 h-100 d-flex justify-content-center align-items-center">

        <div id="show-form" class="card p-5">
            <div class="d-flex gap-5 align-items-center ">
                <h1 class="text-dark ">
                    @foreach ($messages as $message)
                        <p>{{ $message->full_name }}</p>
                        <p>{{ $message->email }}</p>
                        <p>{{ $message->message }}</p>
                        <!-- Altri attributi del messaggio -->
                    @endforeach

                </h1>

            </div>


        </div>
    </section>
@endsection
