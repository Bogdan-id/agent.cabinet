<?php
/**
 * Created by PhpStorm.
 * User: vitalijmihalisin
 * Date: 2019-06-02
 * Time: 22:35
 */

namespace App\Http\Clients;


use GuzzleHttp\Client;

class CalculateClient
{
    protected $client;
    protected $api_key;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('CALC_API_URL')
        ]);

        $this->api_key = env('CALC_API_KEY');
    }

    public function calc() {
        return 'api/calculator.json';
    }

    public function runCalculate($data) : array
    {
      
        $response = $this->client
            ->post('api/calculator.json', [
                'query' => [
                    'authkey' => $this->api_key
                ],
                'json' => $data

            ])
            ->getBody()
            ->getContents();
       
        return json_decode($response, true);
    }
}