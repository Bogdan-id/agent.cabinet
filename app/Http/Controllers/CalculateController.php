<?php

namespace App\Http\Controllers;

use App\Http\Clients\BitrixClient;
use App\Http\Clients\CalculateClient;
use App\Http\Controllers\Controller;
use App\Service\CalculatorDataService;
// use App\Models\Service\CarImageService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use GuzzleHttp\Psr7;
use App\Repositories\CalculationRepository;
use App\Models\Calculation;

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
        CalculationRepository $calculationRepository
     ) {
        try {
            $data = $calculatorDataService->getRequestData();
           
            $params = array_merge($calculateClient->runCalculate(
                $data
            ), [
                'params' => $data
            ]);
            $calculation = $calculationRepository->create($params);
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

        return $calculation;
    }

    /**
     * @param Request $request
     * @param CarImageService $carImageService
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function createExcel (Request $request, CarImageService $carImageService) {
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
                array_merge($request->only([
                    'price',
                    'term',
                    'prepaid',
                    'avg',
                    'rest',
                    'table',
                    'agg',
                    'currency'
                ]), [
                    'car' => $request->get('mark') . ' ' . $request->get('model'),
                    'image' => $carImageService->getFileName(
                        $request->get('mark'),
                        $request->get('model')
                    )
                ])
            )->render()
        );

        $mpdf->Output();
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
}
