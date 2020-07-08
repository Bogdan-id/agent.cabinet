<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    AgentCommission,
};
use App\Mail\{
    AgentAcceptMail,
    AgentRejectMail
};
use Mail;
use App\User;

class AgentCommisionController extends Controller
{
    public function getAgentCommisions()
    {
        $agentCommission = AgentCommission::all();

        return response()->json($agentCommission);
    }
}
