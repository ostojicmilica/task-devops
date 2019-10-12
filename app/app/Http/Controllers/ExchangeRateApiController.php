<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\CountryExchangeRepository;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class ExchangeRateApiController extends Controller
{

    protected $apiExchange;
    protected $repo;

    /**
     * Create a new controller instance.
     *
     * @param ExchangeRateService $apiExchange
     * @param CountryExchangeRepository $repo
     */
    public function __construct(ExchangeRateService $apiExchange, CountryExchangeRepository $repo)
    {
        $this->apiExchange = $apiExchange;
        $this->repo = $repo;
    }

    public function index()
    {
        $exchange_rates = $this->apiExchange->getLatestRates();
        $this->repo->create($exchange_rates);
        dd(($exchange_rates));
    }
}