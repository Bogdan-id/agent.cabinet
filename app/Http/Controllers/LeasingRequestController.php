<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LeasingRequestRequest;


class LeasingRequestController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function create(LeasingRequestRequest $request)
    {
        $data = $request->validated();
        dd($data);
    }
}
