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
use App\Models\OnlineModels\{
    CalculationResult,
    FormField,
    FormOption
};
use App\Http\Clients\OpenDataBotClient;
use App\Http\Clients\BitrixClient;
use GuzzleHttp\Exception\ClientException;
use Carbon\Carbon;

class LeasingRequestController extends Controller
{
    public function __construct(OpenDataBotClient $openDataBotClient, BitrixClient $bitrixClient)
    {
        $this->middleware('auth');
        $this->openDataBotClient = $openDataBotClient;
        $this->bitrixClient = $bitrixClient;
    }

    public function create(LeasingRequestRequest $request)
    {
        $data = $request->validated();
        $leadId = $this->bitrixClient->createLead($data);
        $data['bitrix_id'] = $leadId;
        $leasingRequest = new LeasingRequest;
        $leasingRequest = $leasingRequest->create($data);
        $calculation = $leasingRequest->calculation;
        $requestData = $this->getRequestData($calculation->request_data,  array_merge($calculation->request_data, $calculation->full_request_data), $leadId, $leasingRequest->graph_type);
        $calculation->is_send_request = true;
        $calculation->save();
        $calculationResult = new CalculationResult;
        $calculationResult->request_id = $calculation->request_id;
        $calculationResult->type =  $leasingRequest->graph_type;
        $calculationResult->bitrix_type = 'LEAD_ID';
        $calculationResult->bitrix_id = $leadId;
        $calculationResult->result_data = json_encode($calculation->result_data);
        $calculationResult->request_data = json_encode($requestData);
        $calculationResult->save();

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
            'url' => "storage/{$path}"
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

    public function getRequestData($requestData, $params, $leadId, $graphType)
    {
        $data = [];
        $formFields = FormField::all();
        $formOptions = FormOption::all();

        foreach($formFields as $formField)
        {
          $typeId = $formField->option_type_id;
          switch($typeId ?? null)
          {
            case '72': // Тип предмета лізингу
                $formOptionsByType = $formOptions->filter(function ($value, $key) {
                    return $value->type_id == 72;
                  });
                $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData) {
                    return $value->calculator_value == $requestData['leasingObjectType']['value'];
                  }); 
                $option = $formOption->first();
                $data[$formField->id] = $option->id;
                break;
            case '73':// Програма лізингу
                $formOptionsByType = $formOptions->filter(function ($value, $key) {
                    return $value->type_id == 73;
                  });
                if($requestData['isNew'] === true){
                    $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData) {
                        return $value->name == $requestData['leasedAssertMark']['name'];
                    }); 
                    
                    if($formOption->isEmpty())
                    {
                        $data[$formField->id] = 401;                  
                    }else{
                        $option = $formOption->first();
                        $data[$formField->id] = $option->id;                  
                    }
                }else{
                    $yearCount = $requestData['leasingObjectYear'] + $requestData['leasingTerm'] / 12 - (new Carbon())->year;
                    
                    if (-$yearCount <= 3)
                    {
                        $data[$formField->id] = 412;
                    }elseif(-$yearCount <= 5)
                    {
                        $data[$formField->id] = 413;
                    }else{
                        $data[$formField->id] = 414;
                    }                    
                }
                break;
            case '74': // Валюта дог. лізингу
                $formOptionsByType = $formOptions->filter(function ($value, $key) {
                    return $value->type_id == 74;
                    });
                $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData) {
                  
                    return $value->name === strtoupper($requestData['currency']);
                    });   
                $option = $formOption->first();
                $data[$formField->id] = $option->id;
                break;
            case '75': // Валюта договора с поставщиком (ДФЛ), Валюта договора с поставщиком (ДКП)
                // $formOptionsByType = $formOptions->filter(function ($value, $key) {
                //     return $value->type_id == 74;
                //     });
                // $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData) {
                //     //dd($requestData['currency']);
                //     return $value->name === strtoupper($requestData['currency']);
                //     });   
                // $option = $formOption->first();
                // $data[$formField->id] = $option->id;
                break;
            case '76': // Тип клиента 
                $formOptionsByType = $formOptions->filter(function ($value, $key) {
                    return $value->type_id == 76;
                    });
                $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData) {
                    return $value->calculator_value == $requestData['leasingClientType'];
                    });  

                $option = $formOption->first();
                $data[$formField->id] = $option->id;
                 break;
            case '77': // Джерело заявки
                $formOptionsByType = $formOptions->filter(function ($value, $key) {
                    return $value->type_id == 77;
                    });                    
                $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData) {
                    return $value->calculator_value === '1';
                    });   
                $option = $formOption->first();
                $data[$formField->id] = $option->id;
                break;
            case '78': // Выбранный тип расчетов в график
                    $formOptionsByType = $formOptions->filter(function ($value, $key) {
                        return $value->type_id == 78;
                        });
                    $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData, $graphType) {
                        return $value->calculator_value === $graphType;
                        });   
                    $option = $formOption->first();
                    $data[$formField->id] =["$option->id"];
                    break;
            case '82': // Франшиза
                $formOptionsByType = $formOptions->filter(function ($value, $key) {
                    return $value->type_id == 82;
                    });
                $formOption = $formOptionsByType->filter(function ($value, $key) use($requestData) {
                    return $value->calculator_value == $requestData['insuranceFranchise'];
                    });   
                $option = $formOption->first();
                $data[$formField->id] = $option->id;
                break;
            }
        }
        $autoData = [
            '348' =>  $requestData['leasedAssertMark']['name'],
            '349' =>  $requestData['leasedAssertModel']['name'],
            '350' =>  $requestData['leasingObjectYear'],
            '351' =>  $requestData['leasedAssertEngine'] ?? null,
            '352' =>  $params['leasing-amount'],
            '353' =>  $params['leasing-amount-dkp'] ?? $params['leasing-amount'],
            '356' =>  $params['leasing-currency-course'],
            '357' =>  $params['leasing-currency-course'],
            '360' =>  $params['leasing-quantity'],
            '361' => '1', //Кол-во, ед. (ДКП)
            '364' => '431', //Тип графика
            '365' => $requestData['advance'],
            '366' => $requestData['leasingTerm'],
            '367' => '0', //Остаточная стоимость %
            '368' => 0,
            '369' => $params['vehicle-owner-tax'],
            '370' => '435',
            '372' => $params['patrol-cards-support'],
            '373' => '442',
            '374' => $params['replacement-car-support'],
            '375' => 0, //TODO: 0 -da
            '377' => '471',
            '385' => $params['UNSPR-months'],
            '386' => $params['UNPGA'] * 100,
            '387' => '446', 
            '389' => '453',
            '390' => $params['insurance-casco-manipulate'],
            '391' => '455',
            '392' =>  $params['insurance-lpg'],
            '395' => $params['rate-reduction'] * 100,
            '396' => $params['rate-manipulate'],
            '397' => $params['agent-commission'],
            '398' => $params['commission-manipulate'],
            '399' => $params['commission-lk'],
            '400' => $params['registration'],
            '401' => $params['annual-expenses'],
            '402' => $params['removal-registration'],
            '403' => '472', //Акція
            '410' => $params['pre-financing-days'], // Кількість днів
            '411' => $params['pre-financing-amount'], // Сума, грн.
            '416' => $params['leasing-start-date'], // Дата старту
            '418' => '481',
            'LEAD_ID'=> $leadId
        ];
       
        $result = $data + $autoData;

        return $result;
    }
}
