<?php

namespace App\Http\Controllers;

use App\Http\Clients\BitrixClient;
use App\Http\Clients\CalculateClient;
use App\Http\Controllers\Controller;
use App\Service\CalculatorDataService;
use App\Service\CarImageService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use GuzzleHttp\Psr7;
use App\Repositories\CalculationRepository;
use App\Models\Calculation;
use App\Http\Requests\CalculateRequest;
use App\Mail\OfferPdfMail;
use Mail;

class CalculateController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');

    }
    
    /**
     * @param CalculatorDataService $calculatorDataService
     * @param CalculateClient $calculateClient
     * @param BitrixClient $bitrixClient
     * @return array
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function create (CalculatorDataService $calculatorDataService, 
        CalculateClient $calculateClient,
        BitrixClient $bitrixClient,
        CalculationRepository $calculationRepository,
        CalculateRequest $calculateRequest
     ) {
        try {
            $data = $calculateRequest->validated();
        
            $requestData = $calculatorDataService->getRequestData();
           
            $resultData = $calculateClient->runCalculate($requestData);
        
            $params = array_merge(
            [
                'result_data' => $resultData
            ],
            [
                'request_data' => $data
            ]);
            if(array_key_exists('calculation_id', $params['request_data']))
            {
                $calculation = $calculationRepository->update($params['request_data']['calculation_id'], $params);
            }else{
                $calculation = $calculationRepository->create($params);
            }
          
           // $bitrixClient->addQuoteFromCalculator($calculatorDataService, $params); //Пока нет надобности отправлять предложения
        } catch (RequestException $e) {
            $message = Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                $message = Psr7\str($e->getResponse());
            }
            report($e);
            app('log')->error($message);

            abort($message);
        }

        return response()->json($calculation);
    }

    /**
     * @param Request $request
     * @param CarImageService $carImageService
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function createExcel (Request $request, CarImageService $carImageService) {
        $data = $request->post();
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                resource_path('sass/fonts'),
            ]),
            'fontdata' => $fontData + [
                    'got_bl' => [
                        'R' => 'GothamPro-Black.ttf',
                    ],
                    'got' => [
                        'R' => 'GothamPro.ttf',
                    ],
                    'got_l' => [
                        'R' => 'GothamPro-Light.ttf',
                    ],
                    'got_b' => [
                        'R' => 'GothamPro-Bold.ttf'
                    ]
                ]
        ]);

        $mpdf->WriteHTML(
            view(
                'pdf.index',
                array_merge($data, [
                    'car' => $request->get('mark') . ' ' . $request->get('model'),
                    'image' => $carImageService->getFileName(
                        $request->get('mark'),
                        $request->get('model')
                    )
                ])
            )->render()
        );
        $fileName = "{$data['mark']}_{$data['model']}_{$data['advance']}%_{$data['term']}міс_{$data['currency']}_{$data['requestId']}.pdf";
        if(array_key_exists('email', $data)){
            $mpdf->Output("pdf/$fileName", 'F');
            Mail::to($data['email'])->send(new OfferPdfMail($fileName));
            unlink(public_path("pdf/$fileName"));
        }else{
            $mpdf->Output($fileName, 'D');
        }

        return response()->json([
            'status' => 200
        ]);
    }

    public function getCalculationByAgent($agent_id)
    {
        $agentCalculations = Calculation::where('agent_id', '=', $agent_id)->get();

        return response()->json($agentCalculations);
    }

    public function getCalculationById($id)
    {
        $calculation = Calculation::find($id);

        return response()->json($calculation);
    }

    public function delete($id)
    {
        $calculation = Calculation::find($id);
        abort_if(!$calculation, 422, 'Розрахунок не знайдено!');
        $calculation->delete();

        return response()->json([
            'status' => 200
        ]);
    }
}
