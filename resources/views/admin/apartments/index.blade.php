@extends('layouts.app')

@section('content')

<section id="index" class="w-100 p-5">
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

    <div class="d-md-flex align-items-center flex-wrap mb-4">
        @forelse ($apartments as $apartment)

            <div class="card-content w-50 p-3">
                <a class=" text-decoration-none card flex-grow-1 w-100 h-50 px-0 overflow-hidden" href="{{route('admin.apartments.show',$apartment)}}">
                    <div class="h-100 w-100 d-flex flex-column">
                        <div class="h-100 g-0">
                            <div class="box-img h-100">
                                @if (file_exists(public_path('storage/' . $apartment->image)))
                                    <img src="{{ asset('storage/' . $apartment->image) }}" class="w-100 h-100" alt="{{ $apartment->title }}">
                                @else
                                    <img src="/img/{{$apartment->image}}" class="w-100 h-100" alt="{{ $apartment->title }}">
                                @endif
                            </div>
                        </div>
                        <div class="w-100 text-center">
                            <div class="w-100 p-3">
                                <div class="d-flex w-100 justify-content-center">
                                    <h3 class="card-title fs-1 text-center">{{$apartment->title}}</h3>
                                    <div class="icon d-flex align-items-center ms-3 fs-5">
                                        @if($apartment->visible)
                                        <i class="fa-solid fa-eye text-success my-auto"></i>
                                        @else
                                        <i class="fa-solid fa-eye-slash text-danger my-auto"></i>
                                        @endif
                                    </div>
                                </div>
                                <p class="card-text">Indirizzo di casa: {{$apartment->address}}</p>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="d-flex flex-grow w-100">
                    <a class="nav-link flex-grow-1" href="{{route('admin.apartments.edit', $apartment)}}">
                        <button class="btn btn-warning w-100"><i class="fa-sharp fa-solid fa-pen"></i></button>
                    </a>
                    <a class="nav-link flex-grow-1" href="{{route('admin.messages',$apartment)}}">
                        <button class="btn btn-secondary w-100">
                            <i class="fa-solid fa-message"></i>
                        </button>
                    </a>
                    <form class="flex-grow-1" action={{route("admin.apartments.destroy", $apartment)}} method="post" onsubmit="return confirm('Are you sure you want to delete this apartment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                    <a class="nav-link flex-grow-1" href="{{route('admin.payment', $apartment)}}">
                        <button class="btn btn-success w-100">
                            <i class="fa-solid fa-credit-card"></i>
                        </button>
                    </a>
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
