<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Apartment;
use App\Models\Sponsor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// BRAINTREE
use Braintree;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gateway = new Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchantId'),
            'publicKey' => config('services.braintree.publicKey'),
            'privateKey' => config('services.braintree.privateKey')
        ]);
        $sponsors = Sponsor::all();

        $user_id = Auth::id();
        $apartments = Apartment::where("user_id", $user_id)->get();

        $token = $gateway->ClientToken()->generate();
        return view("admin.sponsor.create", compact('token', 'sponsors', 'apartments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gateway = new Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchantId'),
            'publicKey' => config('services.braintree.publicKey'),
            'privateKey' => config('services.braintree.privateKey')
        ]);

        $nonce = $request->payment_method_nonce;
        $apartment = Apartment::find($request->apartment_id);
        $sponsor = Sponsor::find($request->sponsor_id);

        $start = Carbon::now();
        $end = Carbon::now()->addHours($sponsor->duration)->format('Y-m-d H:i:s');

        $result = $gateway->transaction()->sale([
            'amount' => $sponsor->price,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($result->success) {

            $id_sponsored = DB::table('apartment_sponsor')
            ->where('expiration_date', '>=', $start)
            ->where('apartment_id', '=', $request->apartment_id)
            ->get();

            if ($id_sponsored == null) {
                $sponsor->apartments()->attach($apartment, ['activation_date' => $start, 'expiration_date' => $end]);
            } else {
                $new_start = $start;

                foreach ($id_sponsored as $curr) {
                    if ($curr->expiration_date >= $new_start) {
                        $new_start = $curr->expiration_date;
                    }
                }

                $start = $new_start;
                $new_end = Carbon::parse($new_start);
                $new_end->addHours($sponsor->duration)->format('Y-m-d H:i:s');
                // dd($sponsor->duration);

                $sponsor->apartments()->attach($apartment, ['activation_date' => $start, 'expiration_date' => $new_end]);
            }
            return redirect()->route('admin.apartments.show', $apartment)->with('success_message', 'Il pagamento è avvenuto con successo - ' . $new_end . ' è la data di scadenza per la tua sponsorizzazione');
        } else {
            $errorString = "";

            foreach($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            return back()->withErrors('Il pagamento non è andato a buon fine');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
