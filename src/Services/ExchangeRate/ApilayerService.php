<?php

namespace App\Services\ExchangeRate;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApilayerService implements ExchangeRateInterface
{
    private Client $client;
    private string $apiUrl = "https://api.apilayer.com/exchangerates_data/latest";
    private string $accessKey;

    public function __construct(Client $client)
    {
        $this->accessKey = $_ENV["APILAYER_APIKEY"] ?? "";
        $this->client = $client;
    }

    public function getRateByCurrency(string $currency): float
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl, [
                'headers' => ['apikey' => $this->accessKey]
            ]);
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

        $ratesResult = json_decode($response->getBody(), true);

        if (!$ratesResult["success"]) {
            throw new Exception($ratesResult["error"]["info"]);
        }

        return $ratesResult['rates'][$currency] ?? 0;
    }
}