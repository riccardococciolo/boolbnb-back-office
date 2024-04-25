<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'            => ['required', 'min:5', 'max:150', 'unique:apartments'],
            'price'            => ['required', 'numeric', 'decimal:0, 2'],
            'address'          => ['required'],
            'dimension_mq'     => ['required', 'numeric', 'min:1', 'max:500'],
            'rooms_number'     => ['required', 'numeric', 'min:1', 'max:50'],
            'beds_number'      => ['required', 'numeric', 'min:1', 'max:50'],
            'bathrooms_number' => ['required', 'numeric', 'min:1', 'max:50'],
            'is_visible'       => ['required']
        ];
    }
}
