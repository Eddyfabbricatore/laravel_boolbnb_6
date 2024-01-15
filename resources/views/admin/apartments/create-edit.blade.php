@extends('layouts.app')

@section('content')
    ciao create-edit only apatments

    <form class="col-8 " action="{{route('admin.apartments.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <label for="title">Inserisci un titolo per l'annuncio del tuo appartamento</label>
        <input type="text" name="title" id="title">

        {{-- inserire snippet js upload image --}}
        {{-- <label for="image">Inserisci un titolo per l'annuncio del tuo appartamento</label>
        <input type="text" name="title" id="title"> --}}

        <label for="rooms">Numero stanze</label>
        <input type="number" name="rooms" id="rooms" value="{{old('rooms')}}">

        <label for="bathrooms">Numero di bagni</label>
        <input type="number" name="bathrooms" id="bathrooms">

        <label for="beds">Numero di letti</label>
        <input type="number" name="beds" id="beds">

        <label for="square_meters">Superficie m²</label>
        <input type="number" name="square_meters" id="square_meters">

        {{-- <label for="address">Inserisci l'indirizzo</label>
        <input type="text" name="address" id="address"> --}}
        <div>
            <label for="street_address">Indirizzo (via/piazza/...)</label>
            <input type="text" name="street_address" id="street_address">
        </div>

        <div>
            <label for="street_number">Numero civico</label>
            <input type="text" name="street_number" id="street_number">
        </div>

        <div>
            <label for="city">Città</label>
            <input type="text" name="city" id="city">
        </div>

        <div>
            <label for="province">Provincia</label>
            <input type="text" name="province" id="province">
        </div>

        <div>
            <label for="region">Regione</label>
            <input type="text" name="region" id="region">
        </div>

        <div>
            <label for="country">Nazione</label>
            <input type="text" name="country" id="country">
        </div>

        <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
            @foreach ($services as $service)
                <input type="checkbox" class="btn-check" name="service" id="{{$service->id}}" autocomplete="off">
                <label class="btn btn-outline-primary" for="{{$service->id}}">{{$service->name}}</label>
            @endforeach
        </div>

        <div class="btn-group mt-2" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="visible" id="yes" autocomplete="off" checked value='1'>
            <label class="btn btn-outline-primary" for="yes">Sì</label>

            <input type="radio" class="btn-check" name="visible" id="no" autocomplete="off" value="0">
            <label class="btn btn-outline-primary" for="no">No</label>
        </div>

        <div class="buttons d-block mt-2">
            <button type="submit" class="btn btn-primary">Invia</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>

    </form>
@endsection
