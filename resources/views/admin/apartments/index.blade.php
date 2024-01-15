@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session("success") }}
</div>
@endif

<section class="container">
    <table class="table table-dark table-hover">
        <thead>
        <tr>
            <th scope="col">Titolo</th>
            <th scope="col">Numero stanze</th>
            <th scope="col">Numero bagni</th>
            <th scope="col">Numero camere da letto</th>
            <th scope="col">Metri quadrati</th>
            <th scope="col">Indirizzo</th>
            <th scope="col">Servizi</th>
            <th scope="col">Azioni</th>
        </tr>
        </thead>

        <tbody>
            @foreach ($apartments as $apartment)
            <tr>
            <td>{{$apartment->title}}</td>
            <td>{{$apartment->rooms}}</td>
            <td>{{$apartment->bathrooms}}</td>
            <td>{{$apartment->beds}}</td>
            <td>{{$apartment->square_meters}}</td>
            <td>{{$apartment->address}}</td>
            <td>
                @forelse ($apartment->services as $service)
                    <span class="badge text-bg-dark my-3">{{ $service->name }}</span>
                @empty
                    -
                @endforelse
            </td>
            <td>
                <a class="btn btn-success" href="{{route('admin.apartments.show',$apartment)}}"><i class="fa-solid fa-eye"></i></a>
                <a class="btn btn-warning" href="{{route('admin.apartments.edit',$apartment)}}"><i class="fa-solid fa-pencil"></i></a>
                @include('admin.partials.form_delete',[
                    'route' => route('admin.apartments.destroy', $apartment),
                    'message' => 'Sei sicuro di voler eliminare questo Appartamento?'
                ])
            </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</section>



@endsection
