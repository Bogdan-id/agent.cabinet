<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LeasingRequestRequest;
use App\Repositories\LeasingRequestRepository;


class LeasingRequestController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function create(
                        LeasingRequestRequest $request,
                        LeasingRequestRepository $leasingRequestRepository)
    {
        $data = $request->validated();
        $leasingRequest = $leasingRequestRepository->create($data);

        return response()->json($leasingRequest);
    }
}
