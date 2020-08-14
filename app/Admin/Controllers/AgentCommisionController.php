<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    AgentCommission
};
use App\Mail\{
    AgentAcceptMail,
    AgentRejectMail
};
use Mail;
use App\User;
use App\Events\NewDataEvent;

class AgentCommisionController extends Controller
{
    public function getAgentCommisions()
    {
        $agentCommission = AgentCommission::with('agent', 'leasingRequest')->get();

        return response()->json($agentCommission);
    }

    public function paidAgentCommision($id)
    {
        $agentCommission = AgentCommission::find($id);
        abort_if(!$agentCommission, 422, 'Заявку на виплату АВ не знайдено!');
        $agentCommission->update([
            'status' => 'paid'
        ]);
        event(new NewDataEvent());
        
        return response()->json([
            'status' => 200
        ]);
    }
}
