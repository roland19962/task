<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommissionFeeCalculate extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'input' => [ 'required', 'mimes:csv' ]
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => 'Field name must be input',
            'input.mimes' => 'Field type must be csv'
        ];
    }
}
