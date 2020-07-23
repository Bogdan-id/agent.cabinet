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
use GuzzleHttp\Exception\ClientException;

class LeasingRequestController extends Controller
{
    public function __construct(OpenDataBotClient $openDataBotClient)
    {
        $this->middleware('auth');
        $this->openDataBotClient = $openDataBotClient;
    }

    public function create(LeasingRequestRequest $request)
    {
        $data = $request->validated();
        $leasingRequest = new LeasingRequest;
        $leasingRequest = $leasingRequest->create($data);

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
        try{
            $company = $this->openDataBotClient->getCompanyByEdrpou($edrpou);
            $company = current($company);
            $companyShortName = $company['short_name'];
            $response = [
                'companyShortName' => $companyShortName
            ];
        }catch (ClientException $exception) {
            $statusCode = $exception->getResponse()->getStatusCode();
            $response = [
                'status' => $statusCode
            ];
        }
       
        return response()->json($response);
    }
}
