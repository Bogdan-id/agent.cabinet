<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manager;
use App\Http\Clients\BitrixClient;


class ManagerController extends Controller
{
    public function getManagers()
    {
        $managers = Manager::all();

        return response()->json($managers);
    }

    public function updateManagers(BitrixClient $bitrixClient)
    {
        //$data =  $bitrixClient->getManagers();

        $managers = Manager::all();

        return response()->json($managers);
    }
}
