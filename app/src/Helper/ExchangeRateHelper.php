<?php

namespace App\Helper;

use App\DocumentManager\ExchangeRate;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRateHelper
{
    const EXCHANGE_RATE_API = 'https://api.exchangeratesapi.io/';

    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => self::EXCHANGE_RATE_API]);
    }

    /**
     * @return array|\Exception|GuzzleException
     */
    public function getAllExchangeRates()
    {
        $allRates = [];
        $response = null;

        // example url form exchange rate api
        //https://api.exchangeratesapi.io/latest?base=USD

        foreach (ExchangeRate::CURRENCIES as $currency) {
            try {
                $response = $this->client->request('GET', 'latest', ['query' => ['base' => $currency]]
                );
            } catch (GuzzleException $e) {
                return $e;
            }

            $allRates[] = json_decode($response->getBody()->getContents(), true);
    };

        return $allRates;
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     *
     * @return array|\Exception|GuzzleException
     */
    public function getAllExchangeRatesByDates($dateFrom, $dateTo)
    {
        $allRates = [];
        $response = null;

        $formatDateFrom = date('Y-m-d', strtotime($dateFrom));
        $formatDateTo = date('Y-m-d', strtotime($dateTo));

        // example url form exchange rate api
        //https://api.exchangeratesapi.io/history?start_at=2018-01-01&end_at=2018-09-01

        foreach (ExchangeRate::CURRENCIES as $currency) {
            try {
                $response = $this->client->request('GET', 'history', ['query' => ['base' => $currency, 'start_at'=> $formatDateFrom, 'end_at' => $formatDateTo]]
                );
            } catch (GuzzleException $e) {
                return $e;
            }

            $allRates[] = json_decode($response->getBody()->getContents(), true);
        };

        return $allRates;
    }

    /**
     * @param $currencyIn
     * @param $currencyOut
     *
     * @return \Exception|GuzzleException|mixed|\Psr\Http\Message\ResponseInterface|null
     */
    public function getExchangeRatesByCurrencies($currencyIn, $currencyOut)
    {
        $response = null;

        try {
            $response = $this->client->request('GET', 'latest', ['query' => ['base' => $currencyIn, 'symbols' => $currencyOut]]
            );
        } catch (GuzzleException $e) {
            return $e;
        }
        $rate = json_decode($response->getBody()->getContents(), true);

        return $rate;
    }

    /**
     * @param $currencyIn
     * @param $currencyOut
     * @param $date
     *
     * @return \Exception|GuzzleException|string
     */
    public function getExchangeRatesByCurrenciesAndDate($currencyIn, $currencyOut, $date)
    {
        $response = null;

        $formatDateFrom = date('Y-m-d', strtotime($date));

        // example url form exchange rate api
        //https://api.exchangeratesapi.io/history?start_at=2018-01-01&end_at=2018-09-01

        try {
            $response = $this->client->request('GET', 'history', ['query' => ['base' => $currencyIn, 'start_at'=> $formatDateFrom, 'end_at' => $formatDateFrom, 'symbols'=>$currencyOut]]
            );
        } catch (GuzzleException $e) {
            return $e;
        }

        return  $response->getBody()->getContents();
    }

    /**
     * @param $rates
     *
     * @return mixed
     */
    public function parseExchangeRatesByDateAndCurrencies($rates)
    {
        // "{"rates":{"2019-07-04":{"USD":1.1288}},"start_at":"2019-07-04","base":"EUR","end_at":"2019-07-04"}"

        $data = json_decode($rates, true);
        $newData = reset($data);
        $newValue= reset($newData);
        $rate = reset($newValue);

        return $rate;
    }

}
