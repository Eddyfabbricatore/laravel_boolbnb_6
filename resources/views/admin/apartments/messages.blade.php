@extends('layouts.app')

@section('content')

    <section class="container w-100 h-100 py-3">
        <h1>I tuoi messaggi</h1>

        <div id="show-form" class="card p-5">
            <div class="d-flex gap-5 align-items-center ">

                    @foreach ($messages as $message)
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $message->full_name }}</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">{{ $message->email }}</h6>
                                <p class="card-text">{{ $message->message }}</p>
                            </div>
                        </div>
                    @endforeach

            </div>
        </div>
    </section>
@endsection
