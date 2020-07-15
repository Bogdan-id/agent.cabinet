<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Agent,
    IdCard,
    Passport
};

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAgentNotifications($id)
    {
        $agent = Agent::find($id);
        $agentNotifications = $agent->notifications;

        return response()->json($agentNotifications);
    }
}
