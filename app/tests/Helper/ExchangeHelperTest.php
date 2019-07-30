<?php


namespace App\Tests\Helper;

use App\Helper\ExchangeRateHelper;
use PHPUnit\Framework\TestCase;

class   ExchangeRateHelperTest extends TestCase
{
    public function testParseExchangeRatesByDateAndCurrencies(){

        $helper =  new ExchangeRateHelper();
        $input = "{\"rates\":{\"2019-07-04\":{\"USD\":1.1288}},\"start_at\":\"2019-07-04\",\"base\":\"EUR\",\"end_at\":\"2019-07-04\"}";
        $rate =  $helper->processExchangeRatesByDateAndCurrencies($input);
        $this->assertEquals(1.1288, $rate);
    }

}

