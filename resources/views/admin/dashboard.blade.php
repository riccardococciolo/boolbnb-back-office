@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-4">
                <div class="card">
                    <div class="card-header red-bg text-light">{{ __('Dashboard') }}</div>

                    <div class="card-body gray-bg">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('Sei dentro!') }}
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
