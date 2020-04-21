<?php

namespace App\Http\Requests;

use App\Http\Requests\JsonRequest;

/**
 * @property array leasingObjectType
 * @property array leasedAssertModel
 * @property array leasedAssertMark
 * @property boolean isNew
 * @property integer leasingObjectYear
 * @property integer leasingTerm
 * @property integer leasingAmount
 * @property mixed advance
 * @property mixed leasedAssertEngine
 * @property integer left
 * @property string phone
 * @property string email
 * @property string name
 * @property string currency
 * @property integer insuranceFranchise
 */
class CalculateRequest extends JsonRequest
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
            'agent_id' => 'required|integer',
            'leasingObjectType' => 'required|array',
            'leasingObjectType.value' => 'required|integer',
            'leasingObjectType.label' => 'required|string',

            'leasedAssertMark' => 'required|array',
            'leasedAssertMark.name' => 'required|string',
            'leasedAssertMark.value' => 'required|integer',

            'leasedAssertModel' => 'required|array',
            'leasedAssertModel.name' => 'required|string',
            'leasedAssertModel.value' => 'required|integer',
            
            'leasingObjectYear' => 'required|integer',
            'leasedAssertEngine' => 'required|integer', //Обьем двигателя
            'leasingClientType' => 'required|integer', //Тип клиента
            'currency' => 'required|string',
            'isNew' => 'required|boolean',
            'leasingAmount' => 'required|string',
            'leasingQuantity' => 'required|integer',
            'leasingCurrencyCourse' => 'required|string',
            'graphType' => 'required|array',
            'advance' => 'required|integer',
            'leasingTerm' => 'required|integer',
            'paymentPf' => 'required|boolean',
            'vehicleOwnerTax' => 'required|integer',
            'gpsTrackerModel' => 'required|integer',
            'insuranceProgram' => 'required|integer',
            'insuranceFranchise' => 'required|integer',
            'insuranceVehicleType' => 'required|integer',
        ];
    }
}
