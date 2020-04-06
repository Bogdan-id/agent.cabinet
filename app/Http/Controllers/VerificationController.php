<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Mail\{
    AgentAcceptMail,
    AgentRejectMail
};
use Mail;

class VerificationController extends Controller
{
    public function acceptAgent($id)
    {
        $user = User::find($id);
        $user->is_active = 1;
        $user->save();

        Mail::to($user->email)->send(new AgentAcceptMail($user));

        return response()->json([
            'status' => 200
        ]);
    }

    public function rejectAgent($id)
    {
        $user = User::find($id);

        Mail::to($user->email)->send(new AgentRejectMail($user));

        return response()->json([
            'status' => 200
        ]);
    }
}
