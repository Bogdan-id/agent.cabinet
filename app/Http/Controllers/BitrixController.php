<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcquisitionTarget;

class BitrixController extends Controller
{
    public function getUpdatedLeadId(Request $request)
    {
        $data = $request->post();
        $a = new AcquisitionTarget;
        $a->target = $data['data']['FIELDS']['ID'];
        $a->save();
        //dd($request->all());
    }
}
