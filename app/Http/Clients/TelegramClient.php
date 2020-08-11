<?php

namespace App\Http\Clients;


use GuzzleHttp\Client;

class TelegramClient
{
    protected $client;

    protected $api_key;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('TELEGRAM_URL')
        ]);
        //890440267
        $this->api_key = env('TELEGRAM_TOKEN');
    }

    public function sendMessage($agentCommission)
    {
        $sum = (int) preg_replace("/[^\d]/", "", $agentCommission->leasingRequest->leasing_amount) / 100 * $agentCommission->agent->ab_size;
        $text = "
        Новая заявка на выплату АВ:
        Клієнт - {$agentCommission->leasingRequest->name}
        Сумма АВ - $sum грн
        Агент - {$agentCommission->agent->name}.
        ";

        $response = $this->client
            ->post("/bot{$this->api_key}/sendMessage", [
                'json' => [
                    'chat_id' => env('AGENT_COMMISSION_CHAT_ID'),
                    'text' => $text,
                ],
            ])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}