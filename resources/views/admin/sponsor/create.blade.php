@extends('layouts.admin')

@section('content')
    <div class="container pb-4">
        @if (session('sponsor_active_message'))
            <div class="alert alert-danger">
                {{ session('sponsor_active_message') }}
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="text-center" method="POST" id="payment-form" action="{{ route('admin.sponsor.store') }}">

            @csrf
            @method('POST')

            <section>
                <h4 class="pt-4 pb-2">Seleziona l'appartamento da sponsorizzare</h4>
                <select id="select_apartment" class="form-select w-25 m-auto" aria-label="Default select example"name="apartment_id" required>
                    <option value="" selected disabled hidden>Seleziona</option>
                    @foreach ($apartments as $apartment)
                        <option value="{{ $apartment->id }}">{{ $apartment->title }}</option>
                    @endforeach
                </select>
            </section>

            <section>
                <h4 class="input-label pt-3 pb-2">Seleziona il tipo di sponsorizzazione</h4>
                <div class="card w-25 text-start p-4 m-auto">
                    <div class="form-check">
                        @foreach ($sponsors as $sponsor)
                            <input class="form-check-input" type="radio" id="sponsor_{{ $sponsor->id }}" name="sponsor_id"value="{{ $sponsor->id }}" required>
                            <label for="sponsor_{{ $sponsor->id }}" class="form-check-label">{{ $sponsor->tier . ': ' . $sponsor->duration . 'h - ' . $sponsor->price . '€' }}</label><br>
                        @endforeach
                    </div>
                </div>
                <div class="w-50 m-auto text-start">
                    <div id="bt-dropin"></div>
                </div>
            </section>

            <input id="nonce" name="payment_method_nonce" type="hidden" />
            <button class="btn btn-primary" type="submit"><span>Sponsorizza</span></button>
        </form>
    </div>

    {{-- SCRIPT BRAINTREE --}}

    <script src="https://js.braintreegateway.com/web/dropin/1.25.0/js/dropin.min.js"></script>
    <script>
        const form = document.querySelector('#payment-form');
        const client_token = "{{ $token }}";

        braintree.dropin.create({
            authorization: client_token,
            selector: '#bt-dropin',
        }, function(createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                instance.requestPaymentMethod(function(err, payload) {
                    if (err) {
                        console.log('Request Payment Method Error', err);
                        return;
                    }

                    document.querySelector('#nonce').value = payload.nonce;
                    form.submit();
                });
            });
        });
    </script>

@endsection
