<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AgentCreateRequest;
use App\Models\{
    Agent,
    IdCard,
    Passport
};
use Auth;

class AgentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(AgentCreateRequest $request)
    {
        $data = $request->validated();

        $agent = new Agent;
        $agent->user_id = $data['user_id'];
        $agent->last_name = $data['last_name'];
        $agent->first_name = $data['first_name'];
        $agent->patronymic = $data['patronymic'];
        $agent->company_type = $data['company_type'];
        $agent->company_name = $data['company_name'];
        $agent->position = $data['position'];
        $agent->passport_type_id = $data['passport_type_id'];
        $agent->inn = $data['inn'];
        $agent->birth = $data['birth'];
        $agent->card_number = $data['card_number'];
        $agent->save();

        if($data['passport_type_id'] == 1)
        {
            $passport = new Passport;
            $passport->agent_id = $agent ->id;
            $passport->serie = $data['passport_serie'];
            $passport->passport_number = $data['passport_number'];
            $passport->save();
        }else{
            $idCard = new IdCard;
            $idCard->agent_id = $agent ->id;
            $idCard->unzr_number = $data['passport_serie'];
            $idCard->id_card_number = $data['passport_number'];
            $idCard->save();
        }

        return response()->json([
            'status' => 200
        ]);
    }

    public function update()
    {
       //
    }

    public function getUserAgent()
    {
        $user = Auth::user();
        $agent = Agent::where('user_id', '=', $user->id)->first();
        
        return response()->json([
            'user' => $user,
            'agent' => $agent
        ]);
    }

    public function getAgent($id)
    {
     
        // $agent = Agent::find($id);
        
        // return response()->json([
        //     'user' => $user,
        //     'agent' => $agent
        // ]);
    }

}
