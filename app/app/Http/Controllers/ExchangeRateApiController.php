<?php

namespace App\Http\Controllers;


use App\Repositories\Eloquent\CountryExchangeRepository;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('direct:publisher', ['message' => 'hi soheila']);
    }
}