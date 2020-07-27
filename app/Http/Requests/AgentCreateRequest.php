<?php

namespace App\Http\Requests;

use App\Http\Requests\JsonRequest;
use Illuminate\Http\Request;

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
    public function rules(Request $request)
    {
        $data = $request->post();
        $rules = [
            'user_id' => 'required|integer',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic' => 'string|nullable',
            'company_type' => 'required|string',
            'company_name' => 'string',
            'position' => 'string',
           // 'status' => 'string',
            'passport_type_id' => 'integer',
            'inn' => 'string',
            'birth' => 'string|nullable',
            'card_number' => 'string',
            'iban' => 'string', 
            'oferta_accepted' => 'required|boolean',
            'purpose_of_payment' => 'string', 
        ];

        if(array_key_exists('passport_type_id', $data)){
            $rules['passport_serie'] = 'required|string';
            $rules['passport_number'] = 'required|string';
        }

        return $rules;
    }
}
