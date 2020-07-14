<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentCommisionRequest extends JsonRequest
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
            'agentId' => 'required|integer',
            'leasingRequestId' => 'required|integer|exists:leasing_requests,id|unique:agent_commissions,leasing_requests_id'
        ];
    }

     /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'leasingRequestId.exists' => 'Заявку на лизінг не знайдено!',
            'leasingRequestId.unique' => 'Заявку на виплату АВ вже подано!',
        ];
    }
}
