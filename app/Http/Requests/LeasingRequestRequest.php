<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LeasingRequestRequest extends JsonRequest
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
            'agent_id' =>  'required|integer|exists:agents,id',
            'calculation_id' =>  'required|integer|exists:calculations,id',
            'client_type_id' =>  'required|integer', //TODO: |exists:clients_types,id
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic' => 'required|string',
           // 'region' => 'required|string',
            'leasing_object' => 'required|string',
            'advance' => 'required|integer',
            'leasing_object' => 'required|string',
            'advance' => 'required|integer',
            'leasing_term' => 'required|integer',
            'leasing_amount' => 'required|string',
            'graph_type' => 'required|string',
            'legal_info' => 'required|array',
            'documents' => 'array',
            'email' => 'required|string',
            'phone' => 'required|string'
        ];

        if($data['client_type_id'] === 1){
            $rules['legal_info.inn'] = 'required|string';
        }elseif($data['client_type_id'] === 2){
            $rules['legal_info.edrpou'] = 'required|string';
            $rules['legal_info.company_name'] = 'required|string';
        }

        return $rules;
    }
}
