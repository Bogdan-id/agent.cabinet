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
use DateTime;
use App\Models\Agent;
use GuzzleHttp\Client;

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
    
       $requestData = [
            'agent_id' => $this->calculateRequest->agentId,
            'leasing-object-type' => $this->getObjType(),
            'leasing-program' => $this->getProgram(),
            'leasing-term' => (int) $this->calculateRequest->leasingTerm,
            'leasing-amount' =>  (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasingAmount),
            'advance' => (int) $this->calculateRequest->advance / 100,
            'leased-assert-mark' => ($this->calculateRequest->leasedAssertMark ?? [])['name'] ?? null,
            'leased-assert-model' => ($this->calculateRequest->leasedAssertModel ?? [])['name'] ?? null,
            'leased-assert-engine' =>  (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasedAssertEngine),
            'client-type' => (int) $this->calculateRequest->leasingClientType,  
            'currency' => $this->getCurrency(),
            'leasing-currency' => $this->getLeasingCurrency(),
            'leasing-rest' => (int) $this->calculateRequest->leasingRest / 100,
            'leasing-currency-course' => $this->calculateRequest->leasingCurrencyCourse,
            'payment-PF' => $this->calculateRequest->paymentPf,
            'request-source' => 2,
            'gps-tracker-quantity' => 1,
            'assist-service' => 1,
            'rate-manipulate' => 0.1,
            'agent-commission' => 1,
            'commission-manipulate' => -0.5,
            'commission-lk' => (int) $this->calculateRequest->leasingAmount * 0.005,
            'insurance-franchise' => $this->calculateRequest->insuranceFranchise,
            'insurance-program' => $this->calculateRequest->insuranceProgram,
            'leasing-start-date' => $this->getStartDate(),
            'holidays' => $this->calculateRequest->holidays,
            'UNPGA' => 0.1,
            'pre-financing-days' => 0,
            'leasing-currency' => 1,
            'rate-reduction' => $this->getRateReduction(),
            'pre-financing-amount' => 0,
            'insurance-term' => 1,
            'rate-manipulate' => 0,
            'insurance-casco-manipulate' => 0,
            'agent-commission' => 0,
            'leasing-quantity' => 1,
            'insurance-vehicle-type' => $this->getInsuranceVehicleType(),
            'commission-manipulate' => 0,
            'vehicle-owner-tax' => $this->getVehicleOwnerTax(),
            'insurance-lpg' => 0,
            'commission-lk' => 0,
            'gps-tracker-model' => $this->getGpsTrackerModel(),
            'registration' => 0,
            'annual-expenses' => 0,
            'patrol-cards-support' => 2,
            'removal-registration' => 0,
            'assist-service' => 1,
            'replacement-car-support' => 2,
            'maintenance' => $this->getMaintenance(),
            'commission-lk-pr' => $this->getCommissionLkPr(),
            'state' =>  $this->getState(),
            'Place_of_registration' => $this->getPlaceOfregistration(),
        ];
       
        $graphTypes = $this->calculateRequest->graphType;

        if(in_array('irregular', $graphTypes))
        {
            if($this->calculateRequest->customGraphicType = 5){
                $requestData['custom-graphic-type'] = $this->calculateRequest->customGraphicType;
                $requestData['custom-universal-option'] = $this->calculateRequest->customUniversalOption / 100;
            }elseif($this->calculateRequest->customGraphicType = 3){
                $requestData['custom-graphic-type'] = $this->calculateRequest->customGraphicType;
                $requestData['custom-step-option-first'] = $this->calculateRequest->customStepOptionFirst / 100;
                $requestData['custom-step-option-middle'] = $this->calculateRequest->customStepOptionMiddle / 100;
            }
        };
        
        $requestData['output'] = [
            'sets' => $graphTypes
        ];
        if($this->calculateRequest->leasingAmountDkp){
            $requestData['leasing-amount-dkp'] =  (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasingAmountDkp);
            $requestData['leasing-currency-course-dkp'] = $this->calculateRequest->leasingCurrencyCourse;
        }
        if($this->calculateRequest->stock){
            $requestData['stock'] = $this->calculateRequest->stock;
        }

        if($this->calculateRequest->leasingTerm = 12){
            $requestData['UNSPR-months'] = 11;
        }else{
            $requestData['UNSPR-months'] = 12;
        }
        
        return $requestData;
    }

    /**
     * @return int
     */
    protected function getObjType(): int
    {
        return (int) $this->calculateRequest->leasingObjectType['value'];
    }

    //  /**
    //  * @return int
    //  */
    // protected function getVehicleType(): int
    // {
    //     $engine = $this->calculateRequest->leasedAssertEngine;
    //     switch (true) {
    //         case $engine < 1601:
    //             return 1;
    //         case $engine > 1600 && $engine < 2001:
    //             return 2;
    //         case $engine > 2000 && $engine < 3001:
    //             return 3;
    //         case $engine > 3000:
    //             return 4;
    //         case 'ГАЗ':
    //             return 6;
    //         case 'Ravon':
    //             return 7;
    //         case 'Peugeot':
    //             return 8;
    //         case 'Citroen':
    //             return 9;
    //         case 'Lexus':
    //             return 13;
    //         case 'Mercedes-Benz':
    //             return 14;
    //         default:
    //             return 1;
    //     }
    // }

    /**
     * @return int
     * @throws \Exception
     */
    protected function getProgram(): int
    {
        if($this->calculateRequest->leasingObjectType['value'] === 6)
        {
            $program = $this->getProgramForTrucks();
        }elseif($this->calculateRequest->isNew && $this->calculateRequest->leasingObjectType['value'] !== 6){
            $program = $this->getProgramForNewCar();
        }else{
            $yearCount = (new Carbon())->year - $this->calculateRequest->leasingObjectYear + $this->calculateRequest->leasingTerm / 12;
            if ($yearCount <= 3) return 12;
            if ($yearCount <= 6) return 13;
            
            return 14;
        }

        return $program;
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
            case 'Peugeot':
                return 8;
            case 'Citroen':
                return 9;
            case 'Lexus':
                return 10;
            case 'Mercedes-Benz':
                return 11;
            case 'Fiat':
                return 15;
            case 'Alfa Romeo':
                return 15;
            case 'Mitsubishi':
                return 16;
            case 'Opel':
                return 7;
            default:
                return 1;
        }
    }

    /**
     * @return int
     */
    protected function getStartDate()
    {
        $date = new DateTime(date("Y-m-d"));

        switch (date("d")) {
            case '28':
                $daysPlus = date("t") - 27;
                $date->modify("+{$daysPlus} day");
             break;
            case '29':
               $daysPlus = date("t") - 28;
               $date->modify("+{$daysPlus} day");
            break;
            case '30': 
                $daysPlus = date("t") - 29;
                $date->modify("+{$daysPlus} day");
            break;
            case '31':
                $daysPlus = date("t") - 30;
                $date->modify("+{$daysPlus} day");             
            break;
        }

        return $date->format('Y.m.d');
    }

     /**
     * @return int
     */
    protected function getLeasingCurrency(): int
    {
        switch ( $this->calculateRequest->leasingCurrency ?? null) {
            case 'USD':
                return 2;
            case 'EURO':
                return 3;
            case 'UAH':
                return 1;
        }
    }

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

    private function getPaymentPf($leasingObjectType, $isNew)
    {
        $paymentPf = 2;
        if($leasingObjectType === 1 && $isNew)
        {
            $paymentPf = 1;
        }

        return $paymentPf;
    }

    public function getVehicleOwnerTax()
    {
        $vehicleOwnerTax  = 2;
        $leasingAmount = (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasingAmount) * $this->calculateRequest->leasingCurrencyCourse;
        if($leasingAmount  >  1771000 && $this->calculateRequest->leasingObjectType['value'] === 1 && $this->calculateRequest->isNew || 
        !$this->calculateRequest->isNew && $this->calculateRequest->leasingObjectYear > 2015)
        {
            $vehicleOwnerTax  = 1;
        }

        return $vehicleOwnerTax;
    }

    private function getMaintenance()
    {
        $maintenance = 2;
        if($this->calculateRequest->leasingObjectType['value'] === 1 || $this->calculateRequest->leasingObjectType['value'] === 6)
        {
            $maintenance = 1;
        }

        return $maintenance;
    }

    private function getInsuranceVehicleType()
    {
        $switchData = [
            'objectType' => $this->calculateRequest->leasingObjectType['value'],
            'leasedAssertEngine' => (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasedAssertEngine)
        ];
        switch ($switchData) {
            case $switchData['objectType'] === 6:
                return 6;
            case $switchData['objectType'] === 5:
                return 8;
            case $switchData['objectType'] === 7:
                return 10;
            case $switchData['objectType'] === 1 && $switchData['leasedAssertEngine'] === 0:
                return 7;
            case $switchData['objectType'] === 1 && $switchData['leasedAssertEngine'] <= 1600:
                return 1;
            case $switchData['objectType'] === 1 && $switchData['leasedAssertEngine'] > 1600 && $switchData['leasedAssertEngine'] <= 2000:
                return 2;
            case $switchData['objectType'] === 1 && $switchData['leasedAssertEngine'] > 2000 && $switchData['leasedAssertEngine'] <= 3000:
                return 3;
            case $switchData['objectType'] === 1 && $switchData['leasedAssertEngine'] > 3000:
                return 4;
            default:
                return 9;
            }
    }

    private function getCommissionLkPr()
    {
        // $programProducts = ['Renault', 'Nissan', 'Infiniti', 'Toyota', 'ГАЗ', 'Ravon', 'Peugeot', 'Citroen', 
        //                     'Lexus', 'Mercedes-Benz', 'Fiat', 'Alfa Romeo', 'Mitsubishi', 'Opel'];
        $agent = Agent::find($this->calculateRequest->agentId);
       // $commissionLkPr = $agent->ab_size / 100;
        // if(in_array($this->calculateRequest->leasedAssertMark['name'], $programProducts))
        // {
            $commissionLkPr = ($agent->ab_size - 1) / 100;
            if($agent->ab_size === 0) $commissionLkPr = 0;
           
        //}

        return $commissionLkPr;
    }

    private function getRateReduction()
    {
        $rateReduction = 0;
        if($this->calculateRequest->leasingAmountDkp)
        {
            $dfl = (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasingAmount) * $this->calculateRequest->leasingCurrencyCourse;
            $dkp = (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasingAmountDkp) * $this->calculateRequest->leasingCurrencyCourse;

            $rateReduction = (1 - $dkp/$dfl);
        }

        return $rateReduction;
    }

    private function getGpsTrackerModel()
    {
        $gpsTrackerModel = 2;
        $objTypes = [4, 6, 7, 10];
        if(in_array($this->calculateRequest->leasingObjectType['value'], $objTypes))
        {
            $price =  (int) preg_replace("/[^\d]/", "", $this->calculateRequest->leasingAmount) * $this->calculateRequest->leasingCurrencyCourse;
            $this->client = new Client([
                'base_uri' => 'https://api.privatbank.ua'
            ]);
    
            $response = $this->client->get('/p24api/pubinfo?json&exchange&coursid=5')->getBody()->getContents();
            $result = json_decode($response);
            $usdRate = array_filter($result, function($item){
                if($item->ccy === 'USD') {
                    return $item->buy;
                }               
            });      
            $usdRate = reset($usdRate);
    
            $rate = $usdRate->buy;
            if($price/$rate >= 35000)
            {
                $gpsTrackerModel = 3;
            }
        }

        return $gpsTrackerModel;

    }

    private function getState()
    {
        $state = 2;
        if($this->calculateRequest->isNew)
        {
            $state = 1;
        }

        return $state;
    }

    private function getPlaceOfregistration()
    {
        $place = 1;
        if($this->calculateRequest->leasingObjectType['value'] === 11)
        {
            $place = 2;
        }elseif($this->calculateRequest->leasingObjectType['value'] === 4 || $this->calculateRequest->leasingObjectType['value'] === 10){
            $place = 3;
        }

        return $place;
    }

    private function getProgramForTrucks()
    {
        $autoData = [
            'mark' => $this->calculateRequest->leasedAssertMark['name'],
            'model' => $this->calculateRequest->leasedAssertModel['name']
        ];
        switch ($autoData) {
            case $autoData['mark'] === 'Renault' &&  $this->isModel($autoData['model'], 'Master')||
            $autoData['mark'] === 'Renault' &&  $this->isModel($autoData['model'], 'Trafic')||
            $autoData['mark'] === 'Renault' &&  $this->isModel($autoData['model'], 'Dokker')||
            $autoData['mark'] === 'Renault' &&  $this->isModel($autoData['model'], 'Kangoo'):
                return 2;
            case $autoData['mark'] === 'Mercedes-Benz' &&  $this->isModel($autoData['model'], 'Sprinter')||
            $autoData['mark'] === 'Mercedes-Benz' &&  $this->isModel($autoData['model'], 'Vito')||
            $autoData['mark'] === 'Mercedes-Benz' &&  $this->isModel($autoData['model'], 'Vario'):
                return 11;
            case $autoData['mark'] === 'Peugeot':
                return 8;
            case $autoData['mark'] === 'Citroen':
                return 9;
            case $autoData['mark'] === 'Fiat':
                return 15;
            case $autoData['mark'] === 'Opel':
                return 7;
            case $autoData['mark'] === 'Toyota':
                return 5;
            default:
                return 1;
        }
    }

    private function isModel($model, $needleModel)
    {
        $result = strripos($model, $needleModel);
        if($result !== false)
        {
            $result = true;
        }

        return $result;
    }

}