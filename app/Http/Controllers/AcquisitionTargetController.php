<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcquisitionTarget;

class AcquisitionTargetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAcquisitionTargets()
    {
       $acquisitionTargets = AcquisitionTarget::all();

       return response()->json($acquisitionTargets);
    }
}
