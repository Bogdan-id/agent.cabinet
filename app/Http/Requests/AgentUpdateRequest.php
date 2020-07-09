<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentUpdateRequest extends JsonRequest
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
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic' => 'required|string',
            'company_type' => 'required|string',
            'company_name' => 'required|string',
            'position' => 'required|string',
            'passport_type_id' => 'required|integer',
            'passport_serie' => 'required|string',
            'passport_number' => 'required|string',
            'inn' => 'required|string',
            'birth' => 'required|string',
            'card_number' => 'required|string',
            'iban' => 'required',
            'purpose_of_payment' => 'required|string',
            'phone' =>  'required|string',
            'email' => 'required|string'
        ];
    }
}
