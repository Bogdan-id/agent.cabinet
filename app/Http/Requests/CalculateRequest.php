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
            'leasingObjectType' => 'required',
            'leasedAssertMark' => 'required',
            'leasedAssertModel' => 'required',
            'leasingObjectYear' => 'required',
            'leasedAssertEngine' => 'required', //Обьем двигателя
            'leasingClientType' => 'required', //Тип клиента
            'currency' => 'required',
            'isNew' => 'required|boolean',
            'leasingAmount' => 'required',
            'leasingQuantity' => 'required',
            'leasingCurrencyCourse' => 'required',
            'graphType' => 'required',
            'advance' => 'required',
            'leasingTerm' => 'required',
            'paymentPf' => 'required|boolean',
            'vehicleOwnerTax' => 'required',
            'gpsTrackerModel' => 'required',
            'insuranceProgram' => 'required',
            'insuranceFranchise' => 'required',
        ];
    }
}
