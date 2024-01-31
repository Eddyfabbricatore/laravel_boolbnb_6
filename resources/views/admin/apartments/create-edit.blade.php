@extends('layouts.app')

@section('content')
    <div id="create-edit" class="h-100 w-100 ">
        <a class="position-fixed z-2 top-0 start-50 translate-middle pt-5 my-2 text-light " href="javascript:history.go(-1)">
            <p class="btn mt-4 w-100 h-100 fs-5 btn-outline-light">Torna indietro</p>
        </a>
        <h1 class="text-center text-dark fw-bolder pt-3">{{ $title }}</h1>

        @foreach ($errors as $error)
            {{$error}}
        @endforeach

        <div class="box w-75 m-auto">
            <form class="row rowForm p-3 rounded-4" action="{{$route}}" autocomplete="off" method="POST" enctype="multipart/form-data">
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                @csrf
                @method($method)

                <div class="col-xl-7 col-12">
                    <div class="d-flex flex-column flex-md-row">
                        {{-- TITLE --}}
                        <div class="col-md-7 d-flex flex-column">
                            <label for="title" class="form-label fs-5 w-100">Nome del locale</label>
                            <input
                            type="text"
                            name="title"
                            class="w-100 form-control @error('title') is-invalid @enderror"
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
                        <div class="col-md-3 d-flex flex-column my-2 my-md-0 ms-md-3">
                            <p class="fs-5 mb-2">Rendilo visibile</p>
                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
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
                        @error('visible')
                            <span>{{$message}}</span>
                        @enderror
                    </div>

                    {{-- ADDRESS --}}
                    <div class="mb-3 d-flex align-content-center flex-column">
                        <label for="address" class=" form-label fs-5 w-100">Indirizzo</label>
                        <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-control @error('address') is-invalid @enderror w-100"
                        placeholder="Inserire indirizzo"
                        value="{{old('address', $apartment->address ?? '')}}"
                        required
                        list="autocompleteResults"
                        autocomplete=“off”>

                        <datalist id="autocompleteResults"></datalist>

                        @error('address')
                            <span>{{$message}}</span>
                        @enderror

                    </div>

                    <div>

                        <div class="d-flex flex-column flex-md-row gap-3 fs-5 mb-3">

                            <div class="rooms w-100 d-flex flex-column">

                                {{-- ROOMS --}}
                                <label for="rooms">Numero di stanze</label>
                                <input class="form-control" type="number" name="rooms" placeholder="min: 1" min="1" max="255" id="rooms" value="{{old('rooms', $apartment?->rooms)}}" required placeholder="Stanze disponibili">

                                @error('rooms')
                                    <span>{{$message}}</span>
                                @enderror

                            </div>

                            <div class="bathrooms w-100 d-flex flex-column">

                                {{-- BATHROOMS --}}
                                <label for="bathrooms">Numero di bagni</label>
                                <input class="form-control" type="number" name="bathrooms" placeholder="min: 1" min="1" max="255" id="bathrooms" value="{{old('bathrooms', $apartment?->bathrooms)}}"  required placeholder="Bagni disponibili">

                                @error('bathrooms')
                                    <span>{{$message}}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="d-flex flex-column flex-md-row gap-3 fs-5 mb-3">

                            <div class="beds w-100 d-flex flex-column">

                                {{-- BEDS --}}
                                <label for="beds">Numero di letti</label>
                                <input class="form-control" type="number" name="beds" id="beds" placeholder="min: 1" min="1" max="255" value="{{old('beds', $apartment?->beds)}}"  required placeholder="Letti disponibili">

                                @error('beds')
                                    <span>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="square_meters w-100 d-flex flex-column">

                                {{-- SQUARE_METERS --}}
                                <label for="square_meters">Superficie m²</label>
                                <input
                                class="form-control"
                                type="number"
                                name="square_meters"
                                id="square_meters"
                                min="1"
                                max="65535"
                                value="{{old('square_meters', $apartment?->square_meters)}}"  required placeholder="Inserire metri quadri">

                                @error('square_meters')
                                    <span>{{$message}}</span>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div>

                        {{-- IMAGE --}}
                        <div class="my-2 d-flex flex-column w-100">

                            <div class="d-flex flex-column mb-4">
                                <label for="image" class="form-label fs-5">Immagine</label>
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

                            <img
                            class="img-fluid mx-auto"
                            id="thumb"
                            onerror="this.src='{{asset('/img/Placeholder.png') }}'"
                            src="{{ asset('/storage/'. $apartment?->image) }}"
                            >

                        </div>
                    </div>
                </div>


                {{-- SERVICES --}}
                <div class="col-xl-5 border rounded-3 p-2">

                    <h2 class="text-center">Seleziona i servizi disponibili</h2>

                    <div role="group" class="all-service d-flex flex-wrap justify-content-center rounded-5">

                        @foreach ($services as $index => $service)
                            <div class="box-service m-2">

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
                                    required
                                >

                                <label
                                    class="h-100 btn d-flex flex-column"
                                    for="{{$service->id}}">
                                    <i class="{{ $service->icon }}"></i>{{$service->name}}
                                </label>

                            </div>

                        @endforeach
                    </div>

                </div>

                {{-- BUTTON FORM --}}
                <div class="buttons d-block mt-3 gap-2 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Invia</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
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

        document.addEventListener('DOMContentLoaded', function () {
            var checkboxes = document.querySelectorAll('.my-check');

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    removeRequired(checkbox, checkboxes);
                });
            });

            var atLeastOneChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            checkboxes.forEach(function (checkbox) {
                checkbox.required = !atLeastOneChecked;
            });
        });

        function removeRequired(checkbox, checkboxes) {
            checkboxes.forEach(function (cb) {
                if (cb !== checkbox) {
                    cb.required = !checkbox.checked;
                }
            });
        }


    </script>
    <script src="../../../js/autocomplete.js"></script>

@endsection
