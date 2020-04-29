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
            'agentId' =>  'required|integer|exists:agents,id',
            'calculationId' =>  'required|integer|exists:calculations,id',
            'clientTypeId' =>  'required|integer|exists:client_types,id', //TODO: |exists:clients_types,id
            'lastName' => 'required|string',
            'firstName' => 'required|string',
            'patronymic' => 'required|string',
            'region' => 'required|string',
            'leasingObject' => 'required|string',
            'advance' => 'required|integer',
            'leasingObject' => 'required|string',
            'advance' => 'required|integer',
            'leasingTerm' => 'required|integer',
            'leasingAmount' => 'required|string',
            'graphType' => 'required|string',
            'legalInfo' => 'required|array'
        ];

        if($data['clientTypeId'] === 1){
            $rules['email'] = 'required|string';
            $rules['phone'] = 'required|string';
            $rules['legalInfo.inn'] = 'required|string';
            $rules['legalInfo.monthlyIncome'] = 'required|integer';
            $rules['legalInfo.creditPayment'] = 'required|integer';
            $rules['legalInfo.acquisitionTargetId'] = 'required|integer'; //TODO:  |exists:acquisition_targets,id
        }elseif($data['clientTypeId'] === 2){
            $rules['legalInfo.edrpou'] = 'required|string';
            $rules['legalInfo.companyName'] = 'required|string';
            $rules['legalInfo.equity'] = 'required|string';
            $rules['legalInfo.currencyBalance'] = 'required|integer';
            $rules['legalInfo.creditPayment'] = 'required|integer';
        }

        return $rules;
    }
}
