<?php

use App\Services\ExchangeRateService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ExchangeRateServiceTest extends TestCase
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
        $body = file_get_contents(__DIR__ . '/fixtures/exchange.json');
        $this->mockHandler->append(new Response(200, [], $body));

        $response = $this->apiClient->getLatestRates();
        $this->assertEquals($body, $response);
    }
}
