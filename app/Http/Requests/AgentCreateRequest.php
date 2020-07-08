<?php

namespace App\Http\Requests;

use App\Http\Requests\JsonRequest;

class AgentCreateRequest extends JsonRequest
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
            'user_id' => 'required|integer',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic' => 'required|string',
            'company_type' => 'required|string',
            'company_name' => 'required|string',
            'position' => 'required|string',
           // 'status' => 'string',
            'passport_type_id' => 'required|integer',
            'passport_serie' => 'required|string',
            'passport_number' => 'required|string',
            'inn' => 'required|string',
            'birth' => 'required|string',
            'card_number' => 'required|string',
            'iban' => 'required',
            'oferta_accepted' => 'required|boolean',
            'purposeOfPayment' => 'required|string',
        ];
    }
}
