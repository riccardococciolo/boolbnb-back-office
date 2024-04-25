<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        if($lead->apartment->user_id === Auth::user()->id) {
            return view('admin.leads.show', compact('lead'));
        } else {
            return view('admin.apartments.error404');
        }
    }
}
