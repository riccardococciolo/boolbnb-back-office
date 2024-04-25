@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="card border border-0 text-center col-8 p-2">
                <div class="card-body">
                    <h5 class="card-title fs-4 fw-bold mt-2 mb-2">{{ $lead->first_name }} {{ $lead->last_name }}</h5>
                    <p class="mb-4 text-muted">Email: {{ $lead->email }}</p>
                    <p class="mb-4 text-muted">Data: {{ $lead->created_at }}</p>
                    <p class="w-50 m-auto border border-2 p-3 rounded-2">{{ $lead->message }}</p>

                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Torna Indietro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
