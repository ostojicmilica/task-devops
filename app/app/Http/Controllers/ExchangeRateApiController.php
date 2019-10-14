<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\CountryExchangeRepository;
use App\Services\ExchangeRateService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use Validator;

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

    /**
     * Return exchange rate to user based on x & y currency and given date
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getRate(Request $request)
    {
        $date = $this->getDate($request->input('date'));
        $this->validateInput($request, $date);
        $exchange_rate = $this->repo->findWhereFirst('rate_date', $this->carbonDate($date)->format('Y-m-d'));
        if ($exchange_rate)
            return response()->json($exchange_rate);
        else
            return response()->json([], 404);
    }

    /**
     * Set api date to current date if date not provided by user
     *
     * @param $date
     * @return string
     */
    protected function getDate($date)
    {
        if (!$date)
            return Carbon::today()->format('Y-m-d');
        else
            return $date;
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
     * @param $date
     * @return void
     * @throws ValidationException
     */
    protected function validateInput($request, $date)
    {
        $inputs = [
            'date' => $date,
            'from' => $request->input('from'),
            'to' => $request->input('to')
        ];
        $rules = [
            'date' => 'date|date_format:Y-m-d',
            'from' => 'required|string|max:3|min:3',
            'to' => 'required|string|max:3|min:3'
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