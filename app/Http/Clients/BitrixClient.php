<?php
/**
 * Created by PhpStorm.
 * User: vitalijmihalisin
 * Date: 2019-06-02
 * Time: 22:35
 */

namespace App\Http\Clients;


use App\Models\Service\CalculatorDataService;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class BitrixClient
{
    protected $client;
    private $token;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('BITRIX_API_URL')
        ]);

        $this->token = env('BITRIX_API_TOKEN');
    }

    /**
     * @param CalculatorDataService $calculatorDataService
     * @param array $data
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function addQuoteFromCalculator(CalculatorDataService $calculatorDataService, array $data = [])
    {
        $response = $this->client
         ->post("/rest/3/{$this->token}/crm.quote.add", [
             'json' => [
                 'fields' => $calculatorDataService->getBitrixData($data)
             ]
         ])
         ->getBody()
         ->getContents();

        return json_decode($response, true);
    }

    public function createLeadOrAdd ($name, $phone, $email) {

        $response = $this->client
            ->get("/rest/3/{$this->token}/crm.duplicate.findbycomm", [
                'query' => [
                    'type' => 'EMAIL',
                    'entity_type' => 'LEAD',
                    'values' => [$email]
                ]
            ])
            ->getBody()
            ->getContents();

        $id = Arr::get(json_decode($response, true), 'result.LEAD.0');

        if ($id) return $id;

        $response = $this->client
            ->get("/rest/3/{$this->token}/crm.duplicate.findbycomm", [
                'query' => [
                    'type' => 'PHONE',
                    'entity_type' => 'LEAD',
                    'values' => [$phone]
                ]
            ])
            ->getBody()
            ->getContents();

        $id = Arr::get(json_decode($response, true), 'result.LEAD.0');

        if ($id) return $id;

        $response = $this->client
            ->post("/rest/3/{$this->token}/crm.lead.add", [
                'json' => [
                    'fields' => [
                        "PHONE" => [[
                            "VALUE" => $phone,
                            "VALUE_TYPE" => "WORK"
                        ]],
                        "EMAIL" => [[
                            "VALUE" => $email,
                            "VALUE_TYPE" => "WORK"
                        ]],
                        "NAME" => $name,
                        "TITLE" => "Online калькулятор",
                        "ASSIGNED_BY_ID" => env('BITRIX_ASSIGNED_BY_ID'),
                        "SOURCE_ID" => env('BITRIX_SOURCE_ID'),
                        "STATUS_ID" => "NEW",
                        "OPENED" => "Y"
                    ]
                ]
            ])
            ->getBody()
            ->getContents();

        return  Arr::get(json_decode($response, true), 'result');
    }

    public function searchContact($phone, $email) {
        $response = $this->client
            ->get("/rest/3/{$this->token}/crm.duplicate.findbycomm", [
                'query' => [
                    'type' => 'EMAIL',
                    'entity_type' => 'CONTACT',
                    'values' => [$email]
                ]
            ])
            ->getBody()
            ->getContents();

        $id = Arr::get(json_decode($response, true), 'result.CONTACT.0');
     
        if ($id) return $id;

        $response = $this->client
            ->get("/rest/3/{$this->token}/crm.duplicate.findbycomm", [
                'query' => [
                    'type' => 'PHONE',
                    'entity_type' => 'CONTACT',
                    'values' => [$phone]
                ]
            ])
            ->getBody()
            ->getContents();

        $id = Arr::get(json_decode($response, true), 'result.CONTACT.0');

        if ($id) return $id;

        return null;
    }

    /*
    * Get company id by email or phone
    */
    public function searchCompany($phone, $email) 
    {
        $response = $this->client
            ->get("/rest/3/{$this->token}/crm.duplicate.findbycomm", [
                'query' => [
                    'type' => 'EMAIL',
                    'entity_type' => 'COMPANY',
                    'values' => [$email]
                ]
            ])
            ->getBody()
            ->getContents();

        $id = Arr::get(json_decode($response, true), 'result.COMPANY.0');

        if ($id) return $id;

        $response = $this->client
            ->get("/rest/3/{$this->token}/crm.duplicate.findbycomm", [
                'query' => [
                    'type' => 'PHONE',
                    'entity_type' => 'COMPANY',
                    'values' => [$phone]
                ]
            ])
            ->getBody()
            ->getContents();
        
        $id = Arr::get(json_decode($response, true), 'result.COMPANY.0');
         
        if ($id) return $id;

        return null;
    }

    /*
    * Get or create lead to sending icalc result
    */
    public function getOrCreateLead(array $leadData)
    {
            $response = $this->client
            ->get("/rest/3/{$this->token}/crm.lead.list", [
                'query' => [
                    'filter[EMAIL]' => $leadData['email'],
                    'filter[PHONE]' => $leadData['phone'],
                ]
            ])
            ->getBody()
            ->getContents();

            $result = json_decode($response, true);
           
            if($result['result']){

                $result['ID'] = Arr::get($result, 'result.0.ID');
            }            
        if(!$result['result']){
            $result =  $this->getLeadByParam('EMAIL', $leadData['email']);
        }
        if(!$result){
            $result =  $this->getLeadByParam('PHONE', $leadData['phone']);
        }
        if(!$result){
            $contactId = $this->searchContact($leadData['phone'], $leadData['email']);
            if(!$contactId){
                $companyId = $this->searchCompany($leadData['phone'], $leadData['email']);
                if(!$companyId){
                        $response = $this->client
                        ->post("/rest/3/{$this->token}/crm.lead.add", [
                        'json' => [
                            'fields' => [
                                "PHONE" => [[
                                    "VALUE" => $leadData['phone'],
                                    "VALUE_TYPE" => "WORK"
                                ]],
                                "EMAIL" => [[
                                    "VALUE" => $leadData['email'],
                                    "VALUE_TYPE" => "WORK"
                                ]],
                                "NAME" => $leadData['name'],
                                "TITLE" => "Online калькулятор",
                                "ASSIGNED_BY_ID" => env('BITRIX_ASSIGNED_BY_ID'),
                                "SOURCE_ID" => env('BITRIX_SOURCE_ID'),
                                "STATUS_ID" => "NEW",
                                "OPENED" => "Y"
                            ]
                        ]
                    ])
                    ->getBody()
                    ->getContents();
                $result = json_decode($response, true);
                $result['ID'] = $result['result'];
                }else{
                    $result = $this->getLeadByParam('COMPANY_ID', $companyId);
                    if(!$result){                
                        $response = $this->client
                        ->post("/rest/3/{$this->token}/crm.lead.add", [
                        'json' => [
                            'fields' => [
                                "COMPANY_ID" => $companyId,
                                "TITLE" => "Online калькулятор",
                                "ASSIGNED_BY_ID" => $this->getAssignedById('company', $companyId),
                                "SOURCE_ID" => env('BITRIX_SOURCE_ID'),
                                "STATUS_ID" => "NEW",
                                "OPENED" => "Y"
                                ]
                            ]
                        ])
                        ->getBody()
                        ->getContents();

                        $result = json_decode($response, true);
                        $result['ID'] = $result['result'];
                    }                   
                }       
            }else{
                $result = $this->getLeadByParam('CONTACT_ID', $contactId);
                if(!$result){
                    $response = $this->client
                    ->post("/rest/3/{$this->token}/crm.lead.add", [
                    'json' => [
                        'fields' => [
                            "CONTACT_ID" => $contactId,
                            "TITLE" => "Online калькулятор",
                            "ASSIGNED_BY_ID" => $this->getAssignedById('contact', $contactId),
                            "SOURCE_ID" => env('BITRIX_SOURCE_ID'),
                            "STATUS_ID" => "NEW",
                            "OPENED" => "Y"
                            ]
                        ]
                    ])
                    ->getBody()
                    ->getContents();
                    $result = json_decode($response, true);
                    $result['ID'] = $result['result'];
                }
                  
            }
        }
        
        return $result['ID'];
    }

    public function getLeadByParam($filter, $param) 
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.lead.list", [
                    'query' => [
                        "filter[$filter]" => $param,
                    ]
                ])
                ->getBody()
                ->getContents();
        $result = json_decode($response, true);
       
        return reset($result['result']);
    }

    /**
     * type = contact/company
     */
    public function getAssignedById($type, $id) 
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.{$type}.get", [
                    'query' => [
                        "ID" => $id,
                    ]
                ])
                ->getBody()
                ->getContents();
        $result = json_decode($response, true);

        return $result['result']['ASSIGNED_BY_ID'];
    }

    public function getManagers()
    {
        $response = $this->client
            ->get("/rest/3/{$this->token}/user.search", [
                'query' => [
                     'FILTER' => [
                         'UF_DEPARTMENT' => [
                            // 0 => 19,
                             1 => 21,
                             2 => 93,
                             3 => 95,
                             4 => 97,
                             5 => 99,
                         ],
                         'ACTIVE' => true
                     ]
                ]
            ])
            ->getBody()
            ->getContents();

            $result = json_decode($response, true);
            
            return $result['result'];
    }

    public function getContactById($id)
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.contact.get", [
                    'query' => [
                        "ID" => $id,
                    ]
                ])
                ->getBody()
                ->getContents();

        $result = json_decode($response, true);
        
        return $result['result'];
    }

    public function getCompanyById($id)
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.company.get", [
                    'query' => [
                        "ID" => $id,
                    ]
                ])
                ->getBody()
                ->getContents();

        $result = json_decode($response, true);

        return $result['result'];
    }

    public function createContact($attributes)
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.contact.add", [
                    'query' => [
                        'fields' => [
                            "NAME" => $attributes['name'],
                            "SECOND_NAME" => $attributes['second_name'],
                            "LAST_NAME" => $attributes['last_name'],
                            "OPENED" => 'Y',
                            "STATUS_ID" => "NEW"
                            ]
                    ]
                ])
                ->getBody()
                ->getContents();

        $result = json_decode($response, true);

        return $result['result'];
    }

    public function createCompany($attributes)
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.company.add", [
                    'query' => [
                        'fields' => [
                            "TITLE" => $attributes['company_name'],
                            ]
                    ]
                ])
                ->getBody()
                ->getContents();

        $result = json_decode($response, true);

        return $result['result'];
    }

    public function setContactCompany($contactId, $companyId)
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.contact.company.add", [
                    'query' => [
                        'ID' => $contactId, 
                        'fields' => [
                            "COMPANY_ID" => $companyId,
                            ]
                    ]
                ])
                ->getBody()
                ->getContents();

        $result = json_decode($response, true);

        return $result['result'];
    }
}