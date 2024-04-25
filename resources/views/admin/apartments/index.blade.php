@extends('layouts.admin')

@section('content')
    <div class="container">
        @if(session('message'))
            <div class="alert alert-danger my-2">
                {{ session('message') }}
            </div>
        @endif
        <div class="text-end my-4">
            <a class="btn btn-success" href="{{route('admin.apartments.create')}}">Crea nuovo appartamento</a>
        </div>
        @if (count($apartments) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Titolo</th>
                        <th scope="col">Prezzo</th>
                        <th scope="col">Disponibilità</th>
                        <th scope="col">Sponsorizzazione</th>
                        <th scope="col">Messaggi</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($apartments as $apartment)

                        {{-- @dd(count($apartment->sponsors) == 0 ? 'happy' : 'sad'); --}}

                        <tr>
                            <th scope="row">{{$apartment->title}}</th>
                            <td>
                                {{$apartment->price}}$
                            </td>
                            <td>
                                {{$apartment->is_visible === 1 ? 'Disponibile' : 'Non disponibile'}}
                            </td>
                            @if (count($apartment->sponsors) !== 0 && Carbon\Carbon::now() < $apartment->sponsors[count($apartment->sponsors) - 1]['pivot']['expiration_date'])
                                <td>
                                    Attiva fino al {{$apartment->sponsors[count($apartment->sponsors) - 1]['pivot']['expiration_date']}}
                                </td>
                            @else
                                <td>
                                    Non Attiva
                                </td>
                            @endif
                            <td>
                                {{count($apartment->leads)}}
                            </td>
                            <td>
                                <a class="btn btn-primary" style="width: 40px" href="{{ route('admin.apartments.show', ['apartment' => $apartment->slug]) }}"><i class="fa-solid fa-info"></i></a>
                                <a class="btn btn-warning text-light" style="width: 40px" href="{{ route('admin.apartments.edit', ['apartment' => $apartment->slug]) }}"><i class="fa-solid fa-pencil"></i></a>
                                <form action="{{ route('admin.apartments.destroy', ['apartment' => $apartment->slug]) }}" class="d-inline-block" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-delete" style="width: 40px" type="submit" data-title="{{ $apartment->title }}"><i class="fa-regular fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('partials.delete_modal')
        @else
            <h2 class="py-2">Inserisci qui i tuoi Appartamenti</h2>
        @endif
    </div>
@endsection