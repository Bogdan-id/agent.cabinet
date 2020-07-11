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
           
            $resultData = $this->getCleanData($resultData);

            $params = array_merge(
            [
                'result_data' => $resultData
            ],
            [
                'request_data' => $data
            ]);

            if($calculateRequest->calculation_id)
            {
                $calculation = $calculationRepository->update($calculateRequest->calculation_id, $params);
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
            'margin_left' => 0,
			'margin_right' => 0,
			'margin_top' => 0,
            'margin_bottom' => 0,
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

    protected function getCleanData($resultData)
    {
        if(array_key_exists('even', $resultData))
        {
            $resultData['even']['graph'] = $this->floatToIntGraph($resultData['even']['graph']);
            $resultData['even']['offer-month-payment'] = (int) $resultData['even']['offer-month-payment'];
            $resultData['even']['offer-rest'] = (int)  $resultData['even']['offer-rest'];
            $resultData['even']['offer-price-brutto'] = (int)  $resultData['even']['offer-price-brutto'];

        }
        if(array_key_exists('annuity', $resultData))
        {
            $resultData['annuity']['graph'] = $this->floatToIntGraph($resultData['annuity']['graph']);
            $resultData['annuity']['offer-month-payment'] = (int) $resultData['annuity']['offer-month-payment'];
            $resultData['annuity']['offer-rest'] = (int)  $resultData['annuity']['offer-rest'];
            $resultData['annuity']['offer-price-brutto'] = (int)  $resultData['annuity']['offer-price-brutto'];

        }
        if(array_key_exists('irregular', $resultData))
        {
            $resultData['irregular']['graph'] = $this->floatToIntGraph($resultData['irregular']['graph']);
            $resultData['irregular']['offer-month-payment'] = (int) $resultData['irregular']['offer-month-payment'];
            $resultData['irregular']['offer-rest'] = (int)  $resultData['irregular']['offer-rest'];
            $resultData['irregular']['offer-price-brutto'] = (int)  $resultData['irregular']['offer-price-brutto'];

        }

        return $resultData;
    }

    protected function floatToIntGraph($graph)
    {
        foreach($graph as $key => $value)
        {
            $graph[$key] = [
                'n' => $value['n'],
                'date' => $value['date'],
                'title' => $value['title'],
                'payment-principal' => (int)$value['payment-principal'],
                'interest' =>  (int)$value['interest'],
                'payment' =>  (int)$value['payment'],
            ];
        }

        return $graph;
    }

}
