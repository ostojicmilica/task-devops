<?php

namespace App\Controller;

use App\DocumentManager\ExchangeRate;
use App\DocumentManager\ExchangeRatesHistory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;

class ExchangeRatesController
{
    /**
     * @Route("/rest/{currencyIn}/{currencyOut}", methods={"GET"})
     * @param DocumentManager $dm
     * @param string $currencyIn
     * @param string $currencyOut
     * @return JsonResponse
     */
    public function exchangeRateByCurrencies(DocumentManager $dm, string $currencyIn, string $currencyOut)
    {
        $rate = $dm->getRepository(ExchangeRate::class)->findByName($currencyIn, $currencyOut);

        return new JsonResponse(array('Status' => 'OK', "Rate" => $rate));
    }

    /**
     * @Route("/rest/history/{currencyIn}/{currencyOut}/{date}", methods={"GET"})
     * @param DocumentManager $dm
     * @return JsonResponse
     */
    public function exchangeRateByCurrenciesByDate(DocumentManager $dm, string $currencyIn, string $currencyOut, $date)
    {
        $rate = $dm->getRepository(ExchangeRatesHistory::class)->findByName($currencyIn, $currencyOut, $date);


         return new JsonResponse(array('Status' => 'OK', "Rate" => $rate));
    }
}