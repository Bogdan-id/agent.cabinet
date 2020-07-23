<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{
    AgentCreateRequest,
    AgentUpdateRequest
};
use App\Http\Clients\BitrixClient;
use App\User;
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
        $agent = $agent->create($data);
        if($agent->passport_type_id){
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
        }
        
        return response()->json([
            'status' => 200
        ]);
    }

    public function update($id, AgentUpdateRequest $request)
    {
        $data = $request->validated();
        $agent = Agent::find($id);
        $agent->update($data);
        $user = User::find($agent->user_id);

        $user->update([
            'name' => $data['first_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);
        if(array_key_exists('passport_type_id', $data)){
            if($data['passport_type_id'] == 1)
            {
                $passportExists = Passport::where('agent_id', '=', $agent->id)->first();
                if(!$passportExists){
                    $passport->agent_id = $agent->id;
                    $passport->serie = $data['passport_serie'];
                    $passport->passport_number = $data['passport_number'];
                    $passport->save();
                }else{
                    $passportExists->update([
                        'serie' => $data['passport_serie'],
                        'passport_number' =>  $data['passport_number']
                    ]);
                }
            }else{
                $idCardExists = IdCard::where('agent_id', '=', $agent->id)->first();
                if(!$idCardExists){
                $idCard->agent_id = $agent ->id;
                $idCard->unzr_number = $data['passport_serie'];
                $idCard->id_card_number = $data['passport_number'];
                $idCard->save();
                }else{
                    $idCardExists->update([
                        'unzr_number' => $data['passport_serie'],
                        'id_card_number' =>  $data['passport_number']
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 200
        ]);
    }

    public function getUserAgent()
    {
        $user = Auth::user();
        $agent = Agent::where('user_id', '=', $user->id)->with('manager')->first();
        if($agent && $agent->passport_type_id){
            switch ($agent->passport_type_id) {
                case 1:
                    $document = Passport::where('agent_id', '=', $agent->id)->first();
                    break;
                case 2:
                    $document = IdCard::where('agent_id', '=', $agent->id)->first();
                    break;
            }

            $agent->document = $document;
        }


        return response()->json([
            'user' => $user,
            'agent' => $agent
        ]);
    }

    public function getAgentManager($agent_id)
    {
       $agent = Agent::find($agent_id);
       $manager = $agent->manager;

       return response()->json($manager);
    }

    public function getAgentContact(Request $request, BitrixClient $bitrixClient)
    {
        $result = [];
        $data = $request->post();
        $contactId = $bitrixClient->searchContact($data['phone'], $data['email']);
        if($contactId){
            $contact = $bitrixClient->getContactById($contactId);
            $result = [
                'contact' => [
                    'first_name' =>  $contact['NAME'],
                    'last_name' => $contact['LAST_NAME'],
                    'patronymic' => $contact['SECOND_NAME'],
                    'post' => $contact['POST']
                ],
                'company_name' => null
            ];
            if($contact['COMPANY_ID']){
                $company = $bitrixClient->getCompanyById($contact['COMPANY_ID']);
                $result['company_name'] = $company['TITLE'];
            }
        }
     
        return response()->json($result);      
    }

}
