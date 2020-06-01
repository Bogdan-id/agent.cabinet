<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    Agent,
    IdCard,
    Passport
};
use App\User;

class AgentController extends Controller
{
    public function getAgents()
    {
       $agents = Agent::all();

       return response()->json($agents);
    }

    public function getDontActiveUsers()
    {
        $users = User::dontActive()->get();

        return response()->json($users);
    }

    public function adminUpdateAgent(Request $request, $id) 
    {
        $data = $request->post();
        $agent = Agent::find($id);
        $agent->ab_size = $data['abSize'];
        $agent->status = $data['status'];
        $agent->manager_id = $data['managerId'];
        $agent->save();
        
        return response()->json([
            'status' => 200
        ]);
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        $user->is_active = 1;
        $user->save();
        //TODO: отправка на почту
        return response()->json([
            'status' => 200
        ]);
    }

    public function deactivateUser($id)
    {
        $user = User::find($id);
        $user->is_active = 0;
        $user->save();
        //TODO: отправка на почту
        return response()->json([
            'status' => 200
        ]);
    }
}
