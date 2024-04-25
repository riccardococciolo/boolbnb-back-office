<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request) {
        $form_data = $request->validated();

        $lead = new Lead();
        $lead->fill($form_data);
        $lead->save();

        return response()->json([
            'success' => true,
        ]);
    }
}
