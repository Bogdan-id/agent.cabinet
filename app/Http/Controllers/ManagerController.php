<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manager;

class ManagerController extends Controller
{
    public function getManagers()
    {
        $managers = Manager::all();

        return response()->json($managers);
    }
}
