<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{
    LeasingRequestRequest,
    DocumentsUploadRequest
};
use App\Repositories\LeasingRequestRepository;
use App\Models\{
    LeasingRequest,
    Agent
};
use App\Http\Clients\OpenDataBotClient;

class LeasingRequestController extends Controller
{
    public function __construct(OpenDataBotClient $openDataBotClient)
    {
        //$this->middleware('auth');
        $this->openDataBotClient = $openDataBotClient;
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
       $leasingRequests = LeasingRequest::where('agent_id', '=', $agent_id)->with('calculation')->get();
        
       return response()->json($leasingRequests);
    }

    public function documetnUpload(DocumentsUploadRequest $request)
    {
        $request->validated();
        $path = $request->file('doc')->store('documents', 'public');

        return response()->json([
            'url' => url("/storage/{$path}")
        ]);
    }

    public function updateStatus($id)
    {
        //
    }

    public function getCompanyName($edrpou)
    {
        $company = $this->openDataBotClient->getCompanyByEdrpou($edrpou);
        $company = current($company);
        $companyShortName = $company['short_name'];

        return response()->json([
            'companyShortName' => $companyShortName
        ]);
    }
}
