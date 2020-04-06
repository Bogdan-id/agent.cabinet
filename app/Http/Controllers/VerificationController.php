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
        if($user){
            $user->is_active = 1;
            $user->save();
    
            Mail::to($user->email)->send(new AgentAcceptMail($user));
            $response = [
                'status' => 200
            ];
        }else{
            $response = [
                'error' => 'Пользователь не найден'
            ];
        }

        return response()->json($response);
    }

    public function rejectAgent($id)
    {
        $user = User::find($id);
        if($user){
            $user->is_active = 0;
            $user->save();
            Mail::to($user->email)->send(new AgentRejectMail($user));
            $response = [
                'status' => 200
            ];
        }else{
            $response = [
                'error' => 'Пользователь не найден'
            ];
        }
        
        return response()->json($response);
    }
}
