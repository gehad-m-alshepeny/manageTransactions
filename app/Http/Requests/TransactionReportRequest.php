<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->user()->hasPermissionTo('generate_reports')){
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
            'start_date'=> 'required|date',
            'end_date'=> 'required|date',
        ];
    }

    public function messages()
    {
        return [
    
        ];
    }
}