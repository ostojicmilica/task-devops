<?php

namespace App\Repository;

use App\DocumentManager\ExchangeRate;
use App\DocumentManager\ExchangeRatesHistory;
use App\Helper\ExchangeRateHelper;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Exception;


class ExchangeRatesHistoryRepository extends DocumentRepository
{

    /**
     * @param string $currencyIn
     * @param string $currencyOut
     * @param $date
     * @return mixed
     * @throws Exception
     */
    public function findByName(string $currencyIn, string $currencyOut, $date)
    {
        if(!in_array($currencyIn, ExchangeRate::CURRENCIES) || !in_array($currencyOut, ExchangeRate::CURRENCIES)){
            throw new Exception("Not available currency");
        }

        $rateDoc =  $this->findOneByName($currencyIn . "_" . $currencyOut . "_" . $date);

        //fallback if rate is not present in MongoDB
        if($rateDoc != null)
            $rate = $rateDoc->getRate();

        else {
            $helper = new ExchangeRateHelper();
            $newRate = $helper->getExchangeRatesByCurrenciesAndDate($currencyIn, $currencyOut, $date);
            $newValue = $helper->parseExchangeRatesByDateAndCurrencies($newRate);
            $this->save($date, $currencyIn, $currencyOut, $newValue);
            $rate = $newValue;
        }

        return $rate;
    }

    /**
     * @param $rateDate
     * @param $currencyIn
     * @param $currencyOut
     * @param $exchangeValue
     */
    public function save($rateDate, $currencyIn, $currencyOut, $exchangeValue)
    {
        $formatDate = date('Ymd', strtotime($rateDate));
        $exchangeRate = new ExchangeRatesHistory();
        $exchangeRate->setName($currencyIn . "_" . $currencyOut . "_" . $formatDate);
        $exchangeRate->setRate($exchangeValue);
        $this->dm->persist($exchangeRate);
        $this->dm->flush();

    }

}