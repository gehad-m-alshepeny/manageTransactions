<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->user()->hasPermissionTo('record_payment')){
            return false;
       }else{
            return true;
       }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|gt:0',
            'transaction_id'=> 'required|exists:transactions,id',
            'paid_on'=> 'required|date',
            'details'=> 'string',
        ];
    }

    public function messages()
    {
        return [
    
        ];
    }
}