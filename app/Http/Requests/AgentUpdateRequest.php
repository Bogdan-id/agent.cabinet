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
            'patronymic' => 'string',
            'company_type' => 'required|string',
            'company_name' => 'string',
            'position' => 'string',
            'passport_type_id' => 'integer',
            'passport_serie' => 'string',
            'passport_number' => 'string',
            'inn' => 'string',
            'birth' => 'string',
            'card_number' => 'string',
            'iban' => 'string',
            'purpose_of_payment' => 'string',
            'phone' =>  'required|string',
            'email' => 'required|string'
        ];
    }
}
