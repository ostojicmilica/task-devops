<?php


namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRateService
{

    protected $client;
    private $baseUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUrl = env('EXCHANGE_RATE_BASE_URL');
    }

    public function getLatestRates()
    {
        return $this->endpointRequest('/latest');
    }

    public function endpointRequest($uri)
    {
        try {
            $url = $this->baseUrl . $uri;
            $response = $this->client->get($url);
            return $this->response_handler($response->getBody()->getContents());
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        } catch (GuzzleException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function response_handler($response)
    {
        if ($response) {
            $result = json_decode($response, true);
            return [[
                'base' => $result['base'],
                'rate_date' => $result['date'],
                'rates' => json_encode($result['rates']),
            ], $result['rates']['USD']];
        }
        return [];
    }
}