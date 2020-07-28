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
    public function __construct(BitrixClient $bitrixClient)
    {
        $this->middleware('auth');
        $this->bitrixClient = $bitrixClient;
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
        $contact = $this->bitrixClient->searchContact($agent->user->phone, $agent->user->email);
        if(!$contact)
        {
            $contact = $this->bitrixClient->createContact([
                'name' => $agent->first_name,
                'last_name' => $agent->last_name,
                'second_name' => $agent->patronymic,
                'phone' => $agent->user->phone,
                'birth' => $agent->birth,
                'email' => $agent->user->email,
            ]);
            
            // if($agent->company_type == 'dealer') //TODO:
            // {
            //     $company = $this->bitrixClient->createCompany([
            //         'company_name' => $agent->company_name
            //     ]);
            //     $this->bitrixClient->setContactCompany($contact, $company);
            //     $this->bitrixClient->setContactPosition($contact, $agent->position);
            // }
        }
       $agent->bitrix_id = $contact;
       $agent->save();
    //    if($agent->card_number && $agent->iban) TODO:
    //    {
    //        $dd = $this->bitrixClient->setContactRequisite($contact, [
    //            'fio' => $agent->name,
    //            'first_name' => $agent->first_name,
    //            'last_name' => $agent->last_name,
    //            'email' =>  $agent->user->email,
    //            'phone' => $agent->user->phone,
    //         //    'doc_type' => $agent->name,
    //         //    'doc_serie' => $agent->name,
    //         //    'doc_number' => $agent->name,
    //            'inn' => $agent->inn,
    //            'iban' => $agent->iban,
    //        ]);
    //    }
    //    dd($dd);

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
                    $passport = new Passport;
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
                $idCard = new IdCard;
                $idCard->agent_id = $agent->id;
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

    public function getAgentContact(Request $request)
    {
        $result = [];
        $data = $request->post();
        $contactId = $this->bitrixClient->searchContact($data['phone'], $data['email']);
        if($contactId){
            $contact = $this->bitrixClient->getContactById($contactId);
            $result = [
                'contact' => [
                    'first_name' =>  $contact['NAME'],
                    'last_name' => $contact['LAST_NAME'],
                    'patronymic' => $contact['SECOND_NAME'],
                    'post' => $contact['POST']
                ],
                'company' => [
                    'company_name' => null,
                    'company_type' => null
                ]
                
            ];
            if($contact['COMPANY_ID']){
                $company = $this->bitrixClient->getCompanyById($contact['COMPANY_ID']);
                $result['company'] = [
                    'company_name' => $company['TITLE'],
                    'company_type' => $company['COMPANY_TYPE']
                ];
            }
        }
     
        return response()->json($result);      
    }

}
