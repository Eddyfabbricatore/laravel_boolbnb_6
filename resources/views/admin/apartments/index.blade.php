@extends('layouts.app')

@section('content')

<section id="index" class="w-100 p-5">
    <a class="position-fixed z-2 top-0 start-50 translate-middle pt-5 my-2 text-light " href="javascript:history.go(-1)">
        <p class="btn mt-4 w-100 h-100 fs-5 btn-outline-light">Torna indietro</p>
    </a>
    @if (session('success'))
        <div class="display-none" id="successDelete"></div>
        <div class="modal fade opacity-100 top-50 overflow-visible " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5 text-black " id="exampleModalLabel">Eliminazione Appartamento</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-black "> {{ session('success') }} </p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()" id="closeButton" data-bs-dismiss="modal">Chiudi</button>

                </div>
            </div>
            </div>
        </div>

    @endif

        <div class="container_custom">
        <div class="row justify-content-center">
        @forelse ($apartments as $apartment)
            <div class="col-12 d-flex col-md-4">
                <div class="card">
                    <div class="set-icons">
                        {{-- Bottone del setting con MODIFICA E ELIMINA--}}
                        <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-sharp fa-solid fa-gear fs-1"></i>
                        </button>
                        <ul class="dropdown-menu bg-transparent border-0">
                            <li>
                                <a class="nav-link flex-grow-1 mb-2 w-75" href="{{route('admin.apartments.edit', $apartment->slug)}}">
                                    <button class="btn btn-warning w-100">
                                    Modifica
                                        <i class="fa-sharp fa-solid fa-pen"></i>
                                    </button>
                                </a>
                            </li>
                            <li>
                                <form class="flex-grow-1 w-75" action={{route("admin.apartments.destroy", $apartment->slug)}} method="post" onsubmit="return confirm('Are you sure you want to delete this apartment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        Elimina
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <a class=" text-decoration-none overflow-hidden" href="{{route('admin.apartments.show',$apartment->slug)}}">
                    <div class="box-img">
                        @if (file_exists(public_path('storage/' . $apartment->image)))
                            <img src="{{ asset('storage/' . $apartment->image) }}" alt="{{ $apartment->title }}">
                        @else
                            <img src="/img/{{$apartment->image}}" alt="{{ $apartment->title }}">
                        @endif
                    </div>
                    </a>
                        <div class="card-body">
                            <div class="box-text">
                                <div class="icon d-flex align-items-center py-2 fs-5">

                                    <h5 class="card-title me-2 mb-1">{{$apartment->title}}</h5>
                                    @if($apartment->visible)
                                    <i class="fa-solid fa-eye text-success my-auto"></i>
                                    @else
                                    <i class="fa-solid fa-eye-slash text-danger my-auto"></i>
                                    @endif
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted"> {{$apartment->address}}</h6>
                            </div>
                        <div class="row align-items-end">
                            {{-- Sponsor --}}
                            <div class="col-4">
                            <a class="flex-grow-1" href="{{route('admin.payment', $apartment->slug)}}">
                                <button class="btn btn-success">
                                    <div class="pt-1">
                                        <i class="fa-solid fa-credit-card"></i>
                                        <p>Sponsorizza</p>
                                    </div>
                                </button>
                            </a>
                            </div>
                            {{-- MESSAGE --}}
                            <div class="col-4">
                            <a class="nav-link flex-grow-1" href="{{route('admin.messages',$apartment->slug)}}">
                                <button class="btn btn-secondary">
                                    <div class="pt-1">
                                        <i class="fa-solid fa-message"></i>
                                        <p>Messaggi</p>
                                    </div>
                                </button>
                            </a>
                            </div>

                            {{-- STATS --}}
                            <div class="col-4">
                            <a class="nav-link flex-grow-1" href="{{route('admin.stats', $apartment->slug)}}">
                                <button class="btn btn-success">
                                    <div class="pt-1">
                                        <i class="fa-solid fa-chart-line"></i>
                                        <p>Statistiche</p>
                                    </div>
                                </button>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p>Non ci sono appartamenti</p>
            @endforelse
        </div>

</section>


<script>
    let deleteFlag = document.getElementById('successDelete');
    let closeButton = document.getElementById('closeButton');

    if (deleteFlag) {
        exampleModal.classList.add('d-block');
    }


    function closeModal() {
        exampleModal.classList.remove('d-block');
        exampleModal.classList.add('d-none');
    }

</script>


@endsection
