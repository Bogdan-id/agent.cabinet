<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentCommission;
use App\Http\Requests\AgentCommisionRequest;


class AgentCommisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(AgentCommisionRequest $request)
    {
        $data = $request->validated();
        $agentCommission = new AgentCommission;
        $agentCommission->agent_id = $data['agentId'];
        $agentCommission->leasing_request_id = $data['leasingRequestId'];
        $agentCommission->purpose_of_payment = $data['purposeOfPayment'];
        $agentCommission->status = 'new';
        $agentCommission->save();

        return response()->json([
            'status' => 200
        ]);
    }
}
