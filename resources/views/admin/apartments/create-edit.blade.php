@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <form class="col-8 " action="{{$route}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method($method)

        <label for="title">Inserisci un titolo per l'annuncio del tuo appartamento</label>
        <input type="text" name="title" id="title" value="{{ old("title", $apartment?->title) }}">

        <div class="my-2">
            <label for="image" class="form-label">Immagine</label>
            <input
                    type="file"
                    onchange="showImage(event)"
                    class="form-control
                    @error('image')
                    is-invalid
                    @enderror"
                    id="image"
                    name="image"
                    value="{{old('image', $apartment?->image)}}">
            {{-- in caso di errore del caricamento dell'immagine carico il placeholder --}}

            <img id="thumb" width="150" onerror="this.src='/img/Placeholder.png'" src="{{ asset('storage/'. $apartment?->image) }}">


        </div>

        <label for="rooms">Numero stanze</label>
        <input type="number" name="rooms" id="rooms" value="{{old('rooms', $apartment?->rooms)}}">

        <label for="bathrooms">Numero di bagni</label>
        <input type="number" name="bathrooms" id="bathrooms" value="{{old('bathrooms', $apartment?->bathrooms)}}">

        <label for="beds">Numero di letti</label>
        <input type="number" name="beds" id="beds" value="{{old('beds', $apartment?->beds)}}">

        <label for="square_meters">Superficie m²</label>
        <input
          type="number"
          name="square_meters"
          id="square_meters"
          value="{{old('square_meters', $apartment?->square_meters)}}">

        {{-- <label for="address">Inserisci l'indirizzo</label>
        <input type="text" name="address" id="address"> --}}
        <div>
            <label for="street_address">Nome della strada (via/piazza/...)</label>
            <input
              type="text"
              name="street_address"
              id="street_address"
              value="{{old('street_address', $form_data_address['streetName'] ?? '')}}">
        </div>

        <div>
            <label for="street_number">Numero civico</label>
            <input
              type="text"
              name="street_number"
              id="street_number"
              value="{{old('street_number', $form_data_address['streetNumber'] ?? '')}}">
        </div>

        <div>
            <label for="cap">CAP</label>
            <input
              type="text"
              name="cap"
              id="cap"
              value="{{old('cap', $form_data_address['postalCode'] ?? '')}}">
        </div>

        <div>
            <label for="city">Città</label>
            <input
              type="text"
              name="city"
              id="city"
              value="{{old('city',
              $form_data_address['municipalitySubdivision'] ?? $form_data_address['municipality'] ?? '')}}"
            >
        </div>

        <div>
            <label for="province">Provincia</label>
            <input
              type="text"
              name="province"
              id="province"
              value="{{old('province', $form_data_address['countrySecondarySubdivision'] ?? '')}}">
        </div>

        <div>
            <label for="region">Regione</label>
            <input type="text" name="region" id="region" value="{{old('region', $form_data_address['countrySubdivisionName'] ?? '')}}">
        </div>

        <div>
            <label for="country">Nazione</label>
            <input type="text" name="country" id="country" value="{{old('country', $form_data_address['country'] ?? '')}}">
        </div>

        <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
            @foreach ($services as $service)
                <input type="checkbox" class="btn-check" name="services[]" id="{{$service->id}}" value="{{$service->id}}" autocomplete="off"
                @if (in_array($service->id,old('services',[])))
                    checked
                @elseif($apartment?->services->contains($service))
                    checked
                @endif>
                >
                <label class="btn btn-outline-primary" for="{{$service->id}}">{{$service->name}}</label>
            @endforeach
        </div>
        <div class="btn-group mt-2" role="group" aria-label="Basic radio toggle button group">
            <p class="me-3">Visibile:</p>
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
