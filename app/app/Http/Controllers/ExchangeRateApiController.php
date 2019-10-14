<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\CountryExchangeRepository;
use App\Services\ExchangeRateService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator;

class ExchangeRateApiController extends Controller
{

    protected $apiExchange;
    protected $repo;
    protected $rateFrom;
    protected $rateTo;
    protected $rateDate;
    protected $exchangeRate;
    protected $exchangeBase = 'EUR';
    protected $validCurrency = ['EUR', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK', 'GBP', 'HKD', 'HRK',
        'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JPY', 'KRW', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK',
        'SGD', 'THB', 'TRY', 'USD', 'ZAR'];

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

    /**
     * Return exchange rate to user based on x & y currency and given date
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getRate(Request $request)
    {
        $this->setRateDate($request->input('date'));
        $this->setRateFrom($request->input('from'));
        $this->setRateTo($request->input('to'));
        $this->validateInput($request);

        $this->getExchangeRate();

        if ($this->exchangeRate)
            return response()->json($this->exchangeRate);
        else
            return response()->json([], 404);
    }

    /**
     * get exchange rate values, if exist on cache load from cash otherwise load from database
     *
     * @return void
     */
    private function getExchangeRate()
    {
        try {
            // first check cache
            $fromRateValue = Redis::hget($this->rateDate, $this->rateFrom);
            $toRateValue = Redis::hget($this->rateDate, $this->rateTo);
            // if values not exist on cash load from database
            if (!$fromRateValue or !$toRateValue) {
                //if cache was empty then extract data from database
                $exchange_rate = $this->repo->findWhereLast('rate_date', $this->carbonDate($this->rateDate)->format('Y-m-d'));
                $this->exchangeBase = $exchange_rate['base'];
                $rates = json_decode($exchange_rate['rates']);
                $fromRateValue = property_exists($rates, $this->rateFrom) ? $rates->{$this->rateFrom} : 1;
                $toRateValue = property_exists($rates, $this->rateTo) ? $rates->{$this->rateTo} : 1;;
            }
            $this->calculateRate($fromRateValue, $toRateValue);
        } catch (Exception $e) {
            $this->exchangeRate = Null;
//            echo $e->getMessage();
        }
    }

    /**
     * calculate exchange rate from X to Y
     *
     * @param $from
     * @param $to
     */
    protected function calculateRate($from, $to)
    {
        $this->exchangeRate = ($this->rateFrom == $this->exchangeBase) ? $to : $from / $to;
    }

    /**
     * Set api date to current date if date not provided by user
     *
     * @param $date
     */
    protected function setRateDate($date)
    {
        if (!$date)
            $this->rateDate = Carbon::today()->format('Y-m-d');
        else
            $this->rateDate = $date;
    }

    /**
     * Set exchange from rate key
     *
     * @param $from
     */
    protected function setRateFrom($from)
    {
        $this->rateFrom = strtoupper($from);
    }

    /**
     * Set exchange from rate key
     *
     * @param $to
     */
    protected function setRateTo($to)
    {
        $this->rateTo = strtoupper($to);
    }

    /**
     * Parse Date to string
     *
     * @param $date
     * @return Carbon
     */
    protected function carbonDate($date)
    {
        return Carbon::parse($date);
    }

    /**
     * Validate user inputs
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    protected function validateInput($request)
    {
        $inputs = [
            'date' => $this->rateDate,
            'from' => $this->rateFrom,
            'to' => $this->rateTo
        ];
        $rules = [
            'date' => 'date|date_format:Y-m-d',
            'from' => ['required', Rule::in($this->validCurrency)],
            'to' => ['required', Rule::in($this->validCurrency)],
        ];
        $messages = [
            'date' => "date is not valid",
            'from' => "from currency is required",
            'to' => "to currency is required",
        ];
        $validation = Validator::make($inputs, $rules, $messages);
        if ($validation->fails())
            $this->throwValidationException($request, $validation);
    }
}