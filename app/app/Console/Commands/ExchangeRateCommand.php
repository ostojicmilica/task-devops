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
            $exchange_rates = $apiExchange->getLatestRates();
            $repo->create($exchange_rates);
            $this->info("exchange rates imported successfully");
        } catch (Exception $e) {
            $this->error("error occurred on getting exchange rates");
        }
    }
}