<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    Manager,
};
use App\User;
use App\Http\Clients\BitrixClient;

class ManagersController extends Controller
{
    public function updateManagers(BitrixClient $bitrixClient)
    {
        $managers = $bitrixClient->getManagers();

        foreach($managers as $manager){
            $managerExists = Manager::where('bitrix_id', '=', $manager['ID'])->first();
            if(!$managerExists){
                $managerModel = new Manager;
                $managerModel->name = "{$manager['NAME']} {$manager['LAST_NAME']}";
                $managerModel->email = $manager['EMAIL'];
                $managerModel->phone = $manager['WORK_PHONE'] ?? 'Номер не указан';
                $managerModel->bitrix_id = $manager['ID'];
                $managerModel->photo = $manager['PERSONAL_PHOTO'];
                $managerModel->save();
            }
        }

        return response()->json([
            'status' => 200
        ]);
    }

    public function getManagers()
    {
       $managers = Manager::all();

       return response()->json($managers);
    }
}