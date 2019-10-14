<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExchangeRateCommandTest extends TestCase
{

    public function testExchangeApiSuccess()
    {

        $this->call("GET", "api/v1/rate", [
            "from" => "USD",
            "to" => "EUR",
            "date" => "2019-10-11",
        ], [], [], ["WWW-Authenticate" => "Basic " . base64_encode("guest:guest"), 'PHP_AUTH_USER' => 'guest', 'PHP_AUTH_PW' => 'guest']);

        $this->assertResponseStatus(200);

    }

}