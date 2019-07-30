<?php

namespace App\Repository;

use App\DocumentManager\ExchangeRate;
use App\Helper\ExchangeRateHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Exception;


class ExchangeRateRepository extends DocumentRepository
{
    /**
     * @param string $currencyIn
     * @param string $currencyOut
     *
     * @return |null
     * @throws Exception
     */
    public function findByName(string $currencyIn, string $currencyOut)
    {
        if(!in_array($currencyIn, ExchangeRate::CURRENCIES) || !in_array($currencyOut, ExchangeRate::CURRENCIES)){
            throw new Exception("Not available currency");
        }

        $rate = null;
        $rateDoc = $this->findOneByName($currencyIn . "_" . $currencyOut);

        //fallback if rate is not present in MongoDB
        if($rateDoc != null)
            $rate = $rateDoc->getRate();
        else{
            $helper = new ExchangeRateHelper();
            $newRate = $helper->getExchangeRatesByCurrencies($currencyIn, $currencyOut);

            $exchangeValue = $newRate['rates'][$currencyOut];

            $this->save($currencyIn, $currencyOut, $exchangeValue);
        }

        return $rate;
    }


    public function save($currencyIn, $currencyOut, $exchangeValue)
    {
        $exchangeRate = new ExchangeRate();
        $exchangeRate->setName($currencyIn . "_" . $currencyOut);
        $exchangeRate->setRate($exchangeValue);
        $this->dm->persist($exchangeRate);
        $this->dm->flush();
    }
}