<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! auth()->user()->hasPermissionTo('create_transaction')){
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
            'payer' => 'required|exists:users,id',
            'due_on'=> 'required|date',
            'vat'=> 'required|numeric|gt:-1',
            'is_vat_inclusive'=> 'required|boolean',
        ];
    }

    public function messages()
    {
        return [];
    }
}