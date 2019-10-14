<?php

namespace App\Console\Commands;

use App\Repositories\Eloquent\CountryExchangeRepository;
use App\Services\ExchangeRateService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ExchangeRateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "get:exchange";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fetch latest exchange rates";


    /**
     * Execute the command for fetching latest exchange rate from third part api.
     *
     * @param ExchangeRateService $apiExchange
     * @param CountryExchangeRepository $repo
     */
    public function handle(ExchangeRateService $apiExchange, CountryExchangeRepository $repo)
    {
        try {
            $apiResult = $apiExchange->getLatestRates();
            if (!empty($apiResult)) {
                $rates = json_decode($apiResult['rates'], true);
                $record = $repo->firstOrCreate(['rate_date' => $apiResult['rate_date'], 'rates->USD' => $rates['USD']], $apiResult);
                $wasCreated = $record->wasRecentlyCreated;
                if ($wasCreated) {
                    // we have new rate for USD, inform services via rabbit
                    $this->call('direct:publisher', [
                        'message' => $rates['USD']
                    ]);
                    // update cache values
                    $this->setCache($apiResult['rate_date'], $rates);
                    $this->info('exchange rates imported successfully');
                }
            } else
                $this->info('no exchange rates to import');

        } catch (Exception $e) {
            $this->error(sprintf('error occurred on getting exchange rates, [%s]', $e->getMessage()));
        }
    }

    private function setCache($date, $rates){
        foreach ($rates as $k=>$v) {
            Redis::hset($date, $k, $v);
        }
    }
}