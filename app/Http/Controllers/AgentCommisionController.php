<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    AgentCommission,
    Agent
};
use App\Http\Requests\AgentCommisionRequest;
use Mail;
use App\Mail\NewAgentCommisionMail;

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
        $agentCommission->leasing_requests_id = $data['leasingRequestId'];
        $agentCommission->save();

        Mail::to(env('NVE_EMAIL'))->send(new NewAgentCommisionMail($agentCommission->leasingRequest, $agentCommission->agent));

        return response()->json([
            'status' => 200
        ]);
    }

    public function getAgentCommissions($agent_id)
    {
       $agent = Agent::find($agent_id);
       $commissions = $agent->commissions;

       return response()->json($commissions);
    }
}
