<?php

namespace App\Http\Clients;


use GuzzleHttp\Client;

class OpenDataBotClient
{
    protected $client;

    protected $api_key;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('OPEN_DATA_BOT_URL')
        ]);

        $this->api_key = env('OPEN_DATA_BOT_API_KEY');
    }

    public function getCompanyByEdrpou($edrpou)
    {
        $response = $this->client
            ->get("/api/v2/company/{$edrpou}", [
                'query' => [
                    'apiKey' => $this->api_key
                ],
            ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}