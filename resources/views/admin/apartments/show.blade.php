@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        @if(session('success_message'))
            <div class="alert alert-success my-2 text-center">
                {{ session('success_message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="card border border-0 text-center col-8 p-2">
                <div class="card-body">
                    <h5 class="card-title fs-4 fw-bold my-2">{{ $apartment->title }}</h5>
                    <h6 class="card-subtitle text-muted">{{ $apartment->address }}</h6>
                    <p class="mb-3 text-muted">({{ $apartment->latitude }} , {{ $apartment->longitude }})</p>
                    @if (count($apartment->sponsors) !== 0 && Carbon\Carbon::now() < $apartment->sponsors[count($apartment->sponsors) - 1]['pivot']['expiration_date'])
                        <p class="mb-2 text-success">Sponsor: Attiva fino al {{$apartment->sponsors[count($apartment->sponsors) - 1]['pivot']['expiration_date']}}</p>
                        
                    @else
                        <p class="mb-2 text-warning">Sponsor: Non Attiva</p>
                    @endif
                    <p>Prezzo: <span class="text-danger">{{ $apartment->price }}$</span></p>
                    <p>Dimensione: <span class="text-primary">{{ $apartment->dimension_mq }} mq</span></p>
                    <p>{{ $apartment->rooms_number }} camere da letto - {{ $apartment->beds_number }} letti - {{ $apartment->bathrooms_number }} bagni</p>

                    @if (count($apartment->services) > 0)
                        <p>
                            Servizi disponibili:
                            <div>
                                @foreach ($apartment->services as $service)
                                    <span class="badge border text-dark">
                                        <i class="{{ $service->icon }}"></i>  {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>
                        </p>
                    @endif
                    
                    @if ($apartment->images)
                        <div class="d-flex flex-wrap">
                            @foreach ($apartment->images as $image)
                            <img class="w-25 py-4 m-auto p-2" src="{{ asset('storage/' . $image->image_path) }}" alt="">
                            @endforeach
                        </div>
                    @else
                        <p>Nessuna immagine presente</p>
                    @endif
                    @if (count($leads) > 0)
                        <div class="container w-50 py-2">
                            <h4 class="py-2">I Tuoi Messaggi</h4>
                            <table class="table table-striped border border-2">
                                <thead>
                                    <tr>
                                        <th scope="col">Mittente</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Leggi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads->reverse($leads) as $lead)
                                        <tr>
                                            <td>{{$lead->first_name}} {{$lead->last_name}}</td>
                                            <td>{{$lead->created_at}}</td>
                                            <td>
                                                <a class="btn btn-primary" style="width: 40px" href="{{ route('admin.leads.show', ['lead' => $lead->id]) }}"><i class="fa-solid fa-message"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
