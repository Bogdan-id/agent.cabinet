<?php
/**
 * Created by PhpStorm.
 * User: vitalijmihalisin
 * Date: 2019-05-21
 * Time: 22:31
 */

namespace App\Http\Clients;


use GuzzleHttp\Client;

class AutoRiaClient
{
    protected $client;
    protected $api_key;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('AUTO_RIA_URL')
        ]);

        $this->api_key = env('AUTO_RIA_API');
    }

    public function getMarks ($category) {
        $data = cache()->remember('car_marks_' . $category, now()->addDay(), function () use ($category) {
            return $this->queryMarks($category);
        });

        return collect($data)->sortBy(function ($item) {
            if (in_array($item->name, [
                'Renault',
                'Citroen',
                'Peugeot',
                'Toyota',
                'Lexus',
                'Nissan',
                'Infiniti',
                'ГАЗ',
                'Mercedes-Benz'
            ])) return '1' . $item->name;
            return $item->name;
        })->values()->toArray();
    }

    public function getModels ($category, $mark) {
        $data = cache()->remember('car_marks_' . $category . '_models_' . $mark, now()->addDay(), function () use ($category, $mark) {
            return $this->queryModels($category, $mark);
        });

        return $data;
    }

    protected function queryMarks($category): array
    {
        $response = $this->client->get('/auto/categories/'. $category .'/marks', [
            'query' => [
                'api_key' => $this->api_key
            ]
        ])->getBody()->getContents();

        return json_decode($response);
    }

    protected function queryModels($category, $mark): array
    {
        $response = $this->client->get('/auto/categories/'. $category .'/marks/'. $mark .'/models', [
            'query' => [
                'api_key' => $this->api_key
            ]
        ])->getBody()->getContents();

        return json_decode($response);
    }

    public function getCarById($carId)
    {
        $response = $this->client->get('/auto/info', [
            'query' => [
                'api_key' => $this->api_key,
                'auto_id' => $carId
            ]
        ])->getBody()->getContents();

        return json_decode($response);
    }
}