<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    AgentCommission,
    Agent,
    LeasingRequest
};
use App\Http\Requests\AgentCommisionRequest;
use Mail;
use App\Mail\NewAgentCommisionMail;
use App\Http\Clients\TelegramClient;

class AgentCommisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(AgentCommisionRequest $request, TelegramClient $telegramClient)
    {
        $data = $request->validated();
        $leasingRequest = LeasingRequest::find($data['leasingRequestId']);
        $agent = Agent::find($data['agentId']);
        $av = $agent->ab_size /100;
        $agentCommission = new AgentCommission;
        $agentCommission->agent_id = $data['agentId'];
        $agentCommission->leasing_requests_id = $data['leasingRequestId'];
        $agentCommission->sum = (int) preg_replace("/[^\d]/", "", $leasingRequest->leasing_amount) * $av;
        $agentCommission->price_brutto = $leasingRequest->price_brutto;
        $agentCommission->save();

        Mail::to(env('NVE_EMAIL'))->send(new NewAgentCommisionMail($agentCommission->leasingRequest, $agentCommission->agent));
        $telegramClient->sendMessage($agentCommission);

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
