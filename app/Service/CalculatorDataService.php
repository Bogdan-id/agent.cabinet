<?php
/**
 * Created by PhpStorm.
 * User: vitalijmihalisin
 * Date: 2019-06-06
 * Time: 22:58
 */

namespace App\Service;


use App\Http\Clients\BitrixClient;
use App\Http\Requests\CalculateRequest;
use Carbon\Carbon;

class CalculatorDataService
{
    /**
     * @var CalculateRequest
     */
    private $calculateRequest;
    /**
     * @var PdfService
     */
    private $pdfService;
    /**
     * @var BitrixClient
     */
    private $bitrixClient;

    public function __construct(CalculateRequest $calculateRequest, PdfService $pdfService, BitrixClient $bitrixClient)
    {
        $this->calculateRequest = $calculateRequest;
        $this->pdfService = $pdfService;
        $this->bitrixClient = $bitrixClient;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getRequestData () {
        return [
            'agent_id' => $this->calculateRequest->agent_id,
            'leasing-object-type' => $this->getObjType(),
            'leasing-program' => $this->getProgram(),
            'leasing-term' => (int) $this->calculateRequest->leasingTerm,
            'leasing-amount' =>  (int) $this->calculateRequest->leasingAmount,
            'advance' => (int) $this->calculateRequest->advance / 100,
            'leased-assert-mark' => ($this->calculateRequest->leasedAssertMark ?? [])['name'] ?? null,
            'leased-assert-model' => ($this->calculateRequest->leasedAssertModel ?? [])['name'] ?? null,
            'leased-assert-engine' => (int) $this->calculateRequest->leasedAssertEngine,
            'client-type' => (int) $this->calculateRequest->leasingClientType,    
            'client-type' => (int) $this->calculateRequest->leasingClientType,
            'currency' => $this->getCurrency(),
            'payment-PF' => (int) $this->calculateRequest->paymentPf,
            'vehicle-owner-tax' => (int) $this->calculateRequest->vehicleOwnerTax,
            'gps-tracker-model' =>(int) $this->calculateRequest->gpsTrackerModel, 
            'leasing-rest' => 0,
            'request-source' => 2,
            'gps-tracker-quantity' => 1,
            'assist-service' => 1,
            'rate-manipulate' => 0.1,
            'agent-commission' => 1,
            'commission-manipulate' => -0.5,
            'commission-lk' => (int) $this->calculateRequest->leasingAmount * 0.005,
            'insurance-franchise' => $this->calculateRequest->insuranceFranchise,
            'insurance-program' => $this->getInsuranceProgram(),
           // 'gain-even-graphic-months' => TODO:
           // 'gain-even-graphic-percent' => TODO:
           // 'UNSPR-months' => TODO:
            //'leasing-rest' => $this->calculateRequest->left / 100,
            'output' => [
                'sets' => $this->calculateRequest->graphType
            ]
        ];
    }

    /**
     * @return int
     */
    protected function getObjType(): int
    {
        return (int) $this->calculateRequest->leasingObjectType['value'];
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function getProgram(): int
    {
       
        if ($this->calculateRequest->isNew) return $this->getProgramForNewCar();

        $yearCount = $this->calculateRequest->leasingObjectYear + $this->calculateRequest->leasingTerm / 12 - (new Carbon())->year;

        if ($yearCount <= 3) return 12;
        if ($yearCount <= 5) return 13;
        
        return 14;
    }

    /**
     * @return int
     */
    protected function getProgramForNewCar(): int
    {
        $mark = $this->calculateRequest->leasedAssertMark ?? [];
        switch ($mark['name'] ?? null) {
            case 'Renault':
                return 2;
            case 'Nissan':
                return 3;
            case 'Infiniti':
                return 4;
            case 'Toyota':
                return 5;
            case 'ГАЗ':
                return 6;
            case 'Ravon':
                return 7;
            case 'Peugeot':
                return 8;
            case 'Citroen':
                return 9;
            case 'Lexus':
                return 13;
            case 'Mercedes-Benz':
                return 14;
            default:
                return 1;
        }
    }

    /**
     * @return int
     */
    protected function getCurrency(): int
    {
        switch ( $this->calculateRequest->currency ?? null) {
            case 'USD':
                return 1;
            case 'EURO':
                return 2;
            case 'UAH':
                return 3;
        }
    }

    /**
     * @param array $formApiResponse
     * @return array
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function getBitrixData(array $formApiResponse)
    {
        $data = [
            "TITLE" => " Online калькулятор",
            "NAME" =>  $this->calculateRequest->name,
            "STATUS_ID" => "NEW",
            "OPENED" => "Y",
            "ASSIGNED_BY_ID" => env('BITRIX_ASSIGNED_BY_ID'),
            "UF_CRM_1559894078" => $this->getBitrixObjType(),
            "UF_CRM_1559904735" => $this->getBitrixObjState(),
            "UF_CRM_1559894233" => ($this->calculateRequest->leasedAssertMark ?? [])['name'] ?? null,
            "UF_CRM_1559894247" => ($this->calculateRequest->leasedAssertModel ?? [])['name'] ?? null,
            "UF_CRM_1559894349" => (int) $this->calculateRequest->leasedAssertEngine,
            "UF_CRM_1559906810" => $this->getBitrixYear(),
            "UF_CRM_1559898474" => $this->getBitrixAdvance(),
            "UF_CRM_1559898837" => $this->getBitrixTerm(),
            "UF_CRM_1559898936" => $this->getBitrixLeft(),
            "UF_CRM_1559904594" => $this->getBitrixFile($formApiResponse),
            "UF_CRM_5CA5F1D5E6B28" => $this->getRequestId($formApiResponse),
            "UF_CRM_1564585993" => $this->getInsuranceFranchise(),
            "CURRENCY_ID" =>  $this->calculateRequest->currency === 'usd' ? 'USD' : 'UAH',
            "OPPORTUNITY" => $this->calculateRequest->leasingAmount
        ];


        $contactId = $this->bitrixClient->searchContact(
            $this->calculateRequest->phone,
            $this->calculateRequest->email
        );

        if ($contactId) {
            $data['CONTACT_ID'] = $contactId;
        } else {
            $data['LEAD_ID'] = $this->bitrixClient->createLeadOrAdd(
                $this->calculateRequest->name,
                $this->calculateRequest->phone,
                $this->calculateRequest->email
            );;
        }

        return $data;
    }

    private function getBitrixObjType()
    {
        return (int) $this->calculateRequest->leasingObjectType['bitrix_id'];
    }

    private function getBitrixObjState()
    {
        if ($this->calculateRequest->isNew) return 12119;

        return 12121;
    }

    private function getBitrixYear()
    {
        switch ($this->calculateRequest->leasingObjectYear) {
            case 2019:
                return 12123;
            case 2018:
                return 12125;
            case 2017:
                return 12127;
            case 2016:
                return 12129;
            case 2015:
                return 12131;
            case 2014:
                return 12133;
            case 2013:
                return 12135;
            case 2012:
                return 12137;
            default:
                return $this->calculateRequest->leasingObjectYear;
        }
    }

    private function getBitrixAdvance()
    {
        switch ($this->calculateRequest->advance) {
            case 10:
                return 11985;
            case 15:
                return 11987;
            case 20:
                return 11989;
            case 25:
                return 11991;
            case 30:
                return 11993;
            case 35:
                return 11995;
            case 40:
                return 11997;
            case 45:
                return 11999;
            case 50:
                return 12001;
            case 55:
                return 12003;
            case 60:
                return 12005;
            case 65:
                return 12007;
            case 70:
                return 12009;
            default:
                return $this->calculateRequest->advance;
        }
    }

    private function getBitrixTerm()
    {
        switch ($this->calculateRequest->leasingTerm) {
            case 12:
                return 12011;
            case 24:
                return 12035;
            case 36:
                return 12059;
            case 48:
                return 12083;
            case 60:
                return 12107;
            case 72:
                return 'no';
            case 84:
                return 'no';
            default:
                return $this->calculateRequest->advance;
        }
    }

    private function getBitrixLeft()
    {
        switch ($this->calculateRequest->left) {
            case 0:
                return 12139;
            case 10:
                return 12109;
            case 20:
                return 12111;
            case 30:
                return 12113;
            case 40:
                return 12115;
            case 50:
                return 12117;
            default:
                return $this->calculateRequest->left;
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    private function getBitrixFile(array $data) : array
    {
        return [
            'fileData' => [
                'offer.pdf',
                $this->pdfService->generate($this->calculateRequest, $data)
            ]
        ];
    }

    private function getInsuranceProgram()
    {
        switch ($this->getObjType()) {
            case 1:
            case 3:
                return 2;
            case 8:
                return 3;
            default:
                return 1;
        }

    }

    private function getRequestId(array $data)
    {
        return $data['requestId'];
    }

    private function getInsuranceFranchise()
    {
        switch ($this->calculateRequest->left) {
            case 1:
                return 0.0;
            case 3:
                return 0.3;
            case 8:
                return 0.5;
            default:
                return 1.0;
        }
    }
}