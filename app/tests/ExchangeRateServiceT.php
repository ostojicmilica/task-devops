<?php

use App\Services\ExchangeRateService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ExchangeRateServiceT extends TestCase
{
    protected $apiClient;

    protected $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockHandler = new MockHandler();

        $httpClient = new Client([
            'handler' => $this->mockHandler,
        ]);

        $this->apiClient = new ExchangeRateService($httpClient);
    }

    protected function tearDown(): void
    {
        $this->apiClient = null;
    }


    public function testExchangeService()
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/fixtures/exchange.json')));

        $response = $this->apiClient->getLatestRates();
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/exchange.json'), $response);
    }
}
