@extends('layouts.app')

@section('content')

{{-- @if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session("success") }}
</div>
@endif --}}

<section id="index" class="w-100 row p-5">
@if (session('success'))

<div class="display-none" id="successDelete"></div>

{{-- <div class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminazione Appartamento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>{{session('success')}}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>

        </div>
      </div>
    </div>
  </div> --}}
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



    @forelse ($apartments as $apartment)
        <a class=" text-decoration-none mb-4 card w-100 h-50 px-0 overflow-hidden" href="{{route('admin.apartments.show',$apartment)}}">
            <div class="h-100 w-100 d-flex">
                <div class="h-100 g-0">
                    <div class="box-img h-100">
                        @if (file_exists(public_path('storage/' . $apartment->image)))
                            <img src="{{ asset('storage/' . $apartment->image) }}" class="w-100 h-100" alt="{{ $apartment->title }}">
                        @else
                            <img src="/img/{{$apartment->image}}" class="w-100 h-100" alt="{{ $apartment->title }}">
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h3 class="card-title fs-1 text-center">{{$apartment->title}}</h3>
                        <p class="card-text">Indirizzo di casa: {{$apartment->address}}</p>
                    </div>
                    <div class="icon">
                        @if($apartment->visible)
                            <i class="fa-solid fa-eye"></i>
                        @else
                            <i class="fa-solid fa-eye-slash"></i>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @empty
        <p>Non ci sono appartamenti</p>
    @endforelse
</section>


<script>
    let deleteFlag = document.getElementById('successDelete');

    let closeButton = document.getElementById('closeButton');

    if (deleteFlag) {
        exampleModal.classList.add('d-block');
    }


    function closeModal() {
        exampleModal.classList.remove('d-block')
        exampleModal.classList.add('d-none')
    }

</script>


@endsection
