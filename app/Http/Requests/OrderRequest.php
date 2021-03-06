<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'delivery' => 'required',
            'paymenttype' => 'required',
        ];
    }
}
