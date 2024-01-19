@extends('layouts.app')

@section('content')
    <div id="create-edit" class="h-100 w-100 bg-body-secondary ">
        <h1 class="text-center text-dark fw-bolder pt-3">{{ $title }}</h1>

        @foreach ($errors as $error)
            {{$error}}
        @endforeach

        <div class="box w-75 m-auto">
            <form class="row rowForm p-3 rounded-4" action="{{$route}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method($method)

                <div class="col-7">
                    <div class="d-flex justify-content-around mb-4">
                        {{-- TITLE --}}
                        <div class="mb-3 d-flex justify-content-center flex-column">
                            <label for="title" class="mb-4 form-label fs-2 w-100 text-center">Inserisci il nome del tuo locale</label>
                            <input
                            type="text"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title"
                            value="{{ old("title", $apartment?->title)}}"
                            required
                            minlength="3"
                            maxlength="255"
                            placeholder="Inserire un nome">
                            @error('title')
                                <span>{{$message}}</span>
                            @enderror
                        </div>
                                            {{-- VISIBLE --}}
                        <div class="d-flex flex-column align-content-center justify-content-center">
                            <p class="fs-5 text-center">Rendilo visibile</p>
                            <div class="btn-group mt-2" role="group" aria-label="Basic radio toggle button group">
                                <input
                                    type="radio"
                                    class="btn-check"
                                    name="visible"
                                    id="yes"
                                    autocomplete="off"
                                    checked
                                    value='1'
                                    @if(old('visible', $apartment ? $apartment->visible : '1') == '1')
                                        checked
                                    @endif
                                    >
                                <label class="btn btn-outline-primary" for="yes"><i class="fa-solid fa-eye"></i> Sì</label>

                                <input
                                    type="radio"
                                    class="btn-check"
                                    name="visible"
                                    id="no"
                                    autocomplete="off"
                                    value="0"
                                    @if(old('visible', $apartment ? $apartment->visible : '1') == '0')
                                        checked
                                    @endif
                                    >
                                <label class="btn btn-outline-primary" for="no"><i class="fa-solid fa-eye-slash"></i> No</label>
                            </div>
                        </div>
                    </div>

                    {{-- ADDRESS --}}
                    <div class="my-3 d-flex align-content-center flex-column">
                        <label for="address" class=" form-label fs-4 fw-bold w-100 text-center">Inserisci l'indirizzo del tuo appartamento</label>
                        <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-control @error('address') is-invalid @enderror w-50 m-auto"
                        placeholder="Inserire indirizzo"
                        value="{{old('address', $apartment->address ?? '')}}"
                        required
                        list="autocompleteResults">
                        <datalist id="autocompleteResults"></datalist>
                    </div>

                    <div class="h-25">
                        <div class="d-flex h-50 gap-3">
                            <div class="rooms w-50 d-flex flex-column">
                                {{-- ROOMS --}}
                                <label for="rooms">Numero stanze</label>
                                <input class="form-control" type="number" name="rooms" value="1" min="1" max="255" id="rooms" value="{{old('rooms', $apartment?->rooms)}}" required placeholder="Stanze disponibili">
                            </div>
                            <div class="bathrooms w-50 d-flex flex-column">
                                {{-- BATHROOMS --}}
                                <label for="bathrooms">Numero di bagni</label>
                                <input class="form-control" type="number" name="bathrooms" value="1" min="1" max="255" id="bathrooms" value="{{old('bathrooms', $apartment?->bathrooms)}}"  required placeholder="Bagni disponibili">
                            </div>
                        </div>
                        <div class="d-flex h-50 gap-3">
                            <div class="beds w-50 d-flex flex-column">
                                {{-- BEDS --}}
                                <label for="beds">Numero di letti</label>
                                <input class="form-control" type="number" name="beds" id="beds" value="1" min="1" max="255" value="{{old('beds', $apartment?->beds)}}"  required placeholder="Letti disponibili">
                            </div>
                            <div class="square_meters w-50 d-flex flex-column">
                                {{-- SQUARE_METERS --}}
                                <label for="square_meters">Superficie m²</label>
                                <input
                                class="form-control"
                                type="number"
                                name="square_meters"
                                id="square_meters"
                                value="1"
                                min="1"
                                max="65535"
                                value="{{old('square_meters', $apartment?->square_meters)}}"  required placeholder="Inserire metri quadri">
                            </div>
                        </div>


                    </div>
                    <div class="">
                        {{-- IMAGE --}}
                        <div class="my-2 d-flex justify-content-evenly">
                            <div class="d-flex justify-content-center align-items-center  flex-column ">
                                <label for="image" class="form-label fs-3 fw-bold">Immagine</label>
                                <input
                                type="file"
                                accept="image/*"
                                onchange="showImage(event)"
                                class="form-control
                                @error('image')
                                is-invalid
                                @enderror"
                                id="image"
                                name="image"
                                value="{{old('image', $apartment?->image)}}">
                                {{-- in caso di errore del caricamento dell'immagine carico il placeholder --}}
                            </div>

                            @error('image')
                            <span>{{$message}}</span>
                            @enderror
                            <img id="thumb" onerror="this.src='/img/'.$apartment?->image" src="{{ asset('storage/'. $apartment?->image) }}">
                        </div>
                    </div>



                </div>

                {{-- SERVICES --}}
                <div class="col-5 border rounded-3 p-2">
                    <h2 class="text-center">Servizi disponibili</h2>
                    <div role="group" class="all-service d-flex justify-content-center align-items-center flex-wrap rounded-5">
                        @foreach ($services as $index => $service)
                            <div class="p-2 w-25 box-service">
                                <input
                                    type="checkbox"
                                    class="btn-check my-check"
                                    name="services[]"
                                    id="{{$service->id}}"
                                    autocomplete="off"
                                    value="{{$service->id}}"
                                    @if (in_array($service->id, old('services', [])))
                                        checked
                                    @elseif($apartment?->services->contains($service))
                                        checked
                                    @endif
                                    onclick="removeRequired(this)"
                                    required
                                >

                                <label
                                    class="h-100 btn d-flex flex-column justify-content-around"
                                    for="{{$service->id}}">
                                    <i class="{{ $service->icon }}"></i>{{$service->name}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- BUTTON FORM --}}
                <div class="buttons d-block mt-3 gap-5 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Aggiungi il nuovo locale nel sito.</button>
                    <button type="reset" class="btn btn-danger">Resetta tutti i campi</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

        function showImage(event) {
            const thumb = document.getElementById('thumb');
            thumb.src = URL.createObjectURL(event.target.files[0]);
        }

        function removeRequired(checkbox) {

            var checkboxes = document.querySelectorAll('.my-check');
            checkboxes.forEach(function (cb) {
                if (cb !== checkbox) {
                    cb.required = !checkbox.checked;
                }
            });
        }

    </script>
    <script src="../../../js/autocomplete.js"></script>

@endsection
