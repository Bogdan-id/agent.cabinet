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
use App\Models\Agent;

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

    public function createLeadOrAdd($name, $phone, $email) {

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
    public function createLead(array $leasingRequest)
    {
        $agent = Agent::find($leasingRequest['agent_id']);
        $fields = [
                "PHONE" => [[
                    "VALUE" => $leasingRequest['phone'],
                    "VALUE_TYPE" => "WORK"
                ]],
                "EMAIL" => [[
                    "VALUE" => $leasingRequest['email'],
                    "VALUE_TYPE" => "WORK"
                ]],
                "NAME" => $leasingRequest['first_name'],
                "SECOND_NAME" => $leasingRequest['patronymic'],
                "LAST_NAME" => $leasingRequest['last_name'],
                "TITLE" => "Заявка на лизинг {$leasingRequest['last_name']} {$leasingRequest['first_name']} {$leasingRequest['patronymic']}",
                // "CURRENCY_ID" => 'USD',
                // "OPPORTUNITY" => $leasingRequest['leasing_amount'],
                "UF_CRM_1556027797" => '11275', //Потреби клiента
                "UF_CRM_1571249490" => $agent->bitrix_id, //TODO: agent bitrix id
                "ASSIGNED_BY_ID" => 926,
                "SOURCE_ID" => '55',
                "STATUS_ID" => "NEW",
                "OPENED" => "Y",
                // "UF_CRM_5DA814FAD1B0C" => [
                //     'fileData' => [
                //         'name.jpeg',
                //         base64_encode(file_get_contents('storage/documents/vr9USnEPleMnUgtKhE3BhTOkYqXiyrOJIgdzAkzi.jpeg'))
                //     ]
                // ]
            ];
            if($leasingRequest['client_type_id'] == 1)
            {
                $fields['UF_CRM_5DA81503ED89A'] = '14625'; //Физ.лицо
                $fields['UF_CRM_1556029098'] = $leasingRequest['legal_info']['inn'];

                if($leasingRequest['documents'] && array_key_exists('passport', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935183'] = [
                        'fileData' => [
                            $leasingRequest['documents']['passport']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['passport']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('relatives_passport', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935252'] = [
                        'fileData' => [
                            $leasingRequest['documents']['relatives_passport']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['relatives_passport']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('salary_certificate', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935225'] = [
                        'fileData' => [
                            $leasingRequest['documents']['salary_certificate']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['salary_certificate']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('taxNumber', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935204'] = [
                        'fileData' => [
                            $leasingRequest['documents']['taxNumber']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['taxNumber']['url']))
                        ]
                    ];
                }

            }elseif($leasingRequest['client_type_id'] == 2)
            {
                $fields['UF_CRM_5DA81503ED89A'] = '14627'; // Юр.лицо
                $fields['UF_CRM_1556029073'] = $leasingRequest['legal_info']['edrpou'];
                $fields['COMPANY_TITLE'] = $leasingRequest['legal_info']['company_name'];
                if($leasingRequest['documents'] && array_key_exists('state_registration_certificate', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595934958'] = [
                        'fileData' => [
                            $leasingRequest['documents']['state_registration_certificate']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['state_registration_certificate']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('regulations', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935019'] = [
                        'fileData' => [
                            $leasingRequest['documents']['regulations']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['regulations']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('balance', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935041'] = [
                        'fileData' => [
                            $leasingRequest['documents']['balance']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['balance']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('protocol', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935067'] = [
                        'fileData' => [
                            $leasingRequest['documents']['protocol']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['protocol']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('order', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935092'] = [
                        'fileData' => [
                            $leasingRequest['documents']['order']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['order']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('passport', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935137'] = [
                        'fileData' => [
                            $leasingRequest['documents']['passport']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['passport']['url']))
                        ]
                    ];
                }
                if($leasingRequest['documents'] && array_key_exists('taxNumber', $leasingRequest['documents'])){
                    $fields['UF_CRM_1595935156'] = [
                        'fileData' => [
                            $leasingRequest['documents']['taxNumber']['text'],
                            base64_encode(file_get_contents($leasingRequest['documents']['taxNumber']['url']))
                        ]
                    ];
                }
            }

            $response = $this->client
            ->post("/rest/3/{$this->token}/crm.lead.add", [
            'json' => [
                'fields' => $fields
                ]
            ])
            ->getBody()
            ->getContents();

        $result = json_decode($response, true);
        $result['ID'] = $result['result'];
        
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
                            "TYPE_ID" => "4",
                            "NAME" => $attributes['name'],
                            "SECOND_NAME" => $attributes['second_name'],
                            "LAST_NAME" => $attributes['last_name'],
                            "BIRTHDATE" => $attributes['birth'],
                            "PHONE" => [[
                                "VALUE" => $attributes['phone'],
                                "VALUE_TYPE" => "WORK"
                            ]],
                            "EMAIL" => [[
                                "VALUE" => $attributes['email'],
                                "VALUE_TYPE" => "WORK"
                            ]],
                            "OPENED" => 'Y',
                            "STATUS_ID" => "NEW",
                            "ASSIGNED_BY_ID" => 926, 
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
                            "TITLE" => $attributes['company_name']
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

    public function setContactRequisite($contactId, $requisite)
    {
        $response = $this->client
                ->get("/rest/3/{$this->token}/crm.requisite.add", [
                    'query' => [
                        'fields' => [
                            "ENTITY_TYPE_ID" => 3,
                            "ENTITY_ID" => $contactId,
                            "PRESET_ID" => 1,
                            "NAME" => "Реквізити {$requisite['fio']}",
                        //    "RQ_FIRST_NAME" => $requisite['first_name'],
                        //     "RQ_LAST_NAME" => $requisite['last_name'],
                        //     "RQ_EMAIL" => $requisite['email'],
                        //     "RQ_PHONE" => $requisite['phone'],
                            // "RQ_IDENT_DOC" => $requisite['doc_type'],
                            // "RQ_IDENT_DOC_SER" => $requisite['doc_serie'],
                            // "RQ_IDENT_DOC_NUM" => $requisite['doc_number'],
                            "RQ_INN" => $requisite['inn'],
                           // "RQ_IBAN" => $requisite['iban']
                           "XML_ID"=>"5e4641fd-1dd9-11e6-b2f2-005056c00008",
                            "ACTIVE"=>"Y",
                            "SORT"=>100	
                            ]
                    ]
                ])
                ->getBody()
                ->getContents();

        $result = json_decode($response, true);

        return $result['result'];
    }

    public function setContactPosition($contactId, $position)
    {
        $response = $this->client
        ->get("/rest/3/{$this->token}/crm.contact.update", [
            'query' => [
                "ID" => $contactId,
                'fields' => [
                    "POST" => $position,
                    ]
            ]
        ])
        ->getBody()
        ->getContents();

        $result = json_decode($response, true);

        return $result['result'];
    }

    public function getLeadById($leadId)
    {
        $response = $this->client
        ->get("/rest/3/{$this->token}/crm.lead.get", [
            'query' => [
                "ID" => $leadId,
            ]
        ])
        ->getBody()
        ->getContents();

        $result = json_decode($response, true);

        return $result['result'];
    }
}