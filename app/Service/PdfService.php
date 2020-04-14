<?php
/**
 * Created by PhpStorm.
 * User: vitalijmihalisin
 * Date: 2019-06-23
 * Time: 14:14
 */

namespace App\Service;


use App\Http\Requests\CalculateRequest;
use Illuminate\Support\Arr;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfService
{
    /**
     * @var CarImageService
     */
    private $carImageService;

    public function __construct(CarImageService $carImageService)
    {
        $this->carImageService = $carImageService;
    }

    /**
     * @param CalculateRequest $calculateRequest
     * @param array $apiData
     * @return string
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function generate (CalculateRequest $calculateRequest, array $apiData) {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                resource_path('sass/fonts'),
            ]),
            'fontdata' => $fontData + [
                    'got' => [
                        'R' => 'GothamPro-Black.ttf',
                    ],
                    'got_b' => [
                        'R' => 'GothamPro-Bold.ttf'
                    ]
                ]
        ]);


        $evenPlus = $apiData['even-plus'];

        $mpdf->WriteHTML(
            view(
                'pdf.short',
                [
                    'image' => $this->carImageService->getFileName(
                        Arr::get($calculateRequest->leasedAssertMark, 'name'),
                        Arr::get($calculateRequest->leasedAssertModel, 'name')
                    ),
                    'car' => (($calculateRequest->leasedAssertMark ?? [])['name'] ?? null) . ' ' . (($calculateRequest->leasedAssertModel ?? [])['name'] ?? null),
                    'price' => $calculateRequest->leasingAmount,
                    'term' => $calculateRequest->leasingTerm,
                    'prepaid' => $evenPlus['graph'][0]['payment'],
                    'avg' => $evenPlus['offer-month-payment'],
                    'rest' => $evenPlus['offer-rest'],
                    'table' => $this->graph($evenPlus),
                    'currency' => $calculateRequest->currency === 'uah' ? 'ГРН' : 'USD',
                    'agg' => [
                        'n' => 1,
                        'payment-principal' => $this->getSumOf($evenPlus, 'payment-principal'),
                        'interest' => $this->getSumOf($evenPlus, 'interest'),
                        'payment' => $this->getSumOf($evenPlus, 'payment')
                    ]
                ]
            )->render()
        );

        $file = $mpdf->Output('offer.pdf', Destination::STRING_RETURN);

        return base64_encode($file);
    }

    private function graph(array $evenPlus)
    {
        $data = $evenPlus['graph'];
        unset($data[0]);
        return array_values($data);
    }

    private function getSumOf(array $evenPlus, string $string)
    {
        return array_sum(array_column($this->graph($evenPlus), $string));
    }
}