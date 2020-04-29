<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LeasingRequestRequest;
use App\Repositories\LeasingRequestRepository;
use App\Models\{
    LeasingRequest,
    Agent
};

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

    public function getLeasingRequest($id)
    {
       $leasingRequest = LeasingRequest::find($id);

       return response()->json($leasingRequest);
    }

    public function getLeasingRequestByAgent($agent_id)
    {
       $agent = Agent::find($agent_id);

       $leasingRequests = $agent->leasingRequests;

       return response()->json($leasingRequests);
    }
}
