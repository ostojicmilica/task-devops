<?php

namespace App\Console\Commands;

use App\Repositories\Eloquent\CountryExchangeRepository;
use App\Services\ExchangeRateService;
use Exception;
use Illuminate\Console\Command;

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
     * Execute the console command.
     *
     * @param ExchangeRateService $apiExchange
     * @param CountryExchangeRepository $repo
     */
    public function handle(ExchangeRateService $apiExchange, CountryExchangeRepository $repo)
    {
        try {
            [$apiResult, $rateToCheck] = $apiExchange->getLatestRates();
            if (!empty($apiResult)) {
                $record = $repo->firstOrCreate(['rate_date' => $apiResult['rate_date'], 'rates->USD' => $rateToCheck], $apiResult);
                $wasCreated = $record->wasRecentlyCreated;
                if ($wasCreated) {
                    // we have new rate for USD, inform services via rabbit
                    $this->call('direct:publisher', [
                        'message' => $rateToCheck
                    ]);
                    $this->info('exchange rates imported successfully');
                }
            } else
                $this->info('no exchange rates to import');

        } catch (Exception $e) {
            $this->error(sprintf('error occurred on getting exchange rates, [%s]', $e->getMessage()));
        }
    }
}