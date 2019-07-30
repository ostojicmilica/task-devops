<?php

namespace App\Command;

use App\DocumentManager\ExchangeRate;
use App\Helper\ExchangeRateHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExchangeRatesCommand extends Command
{
    protected static $defaultName = 'ExchangeRates';
    private $dm;
    private $exchangeRateHelper;

    /**
     * ExchangeRatesCommand constructor.
     *
     * @param DocumentManager    $dm
     * @param ExchangeRateHelper $exchangeRateHelper
     */
    public function __construct(DocumentManager $dm, ExchangeRateHelper $exchangeRateHelper)
    {
        parent::__construct();
        $this->dm = $dm;
        $this->exchangeRateHelper = $exchangeRateHelper;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);

        $rates = $this->exchangeRateHelper->getAllExchangeRates();
        $exchangeRateRepository = $this->dm->getRepository(ExchangeRate::class);
        foreach ($rates as $currencyRate) {
            foreach ($currencyRate['rates'] as $currencyOut => $exchangeValue) {
                $currencyIn = $currencyRate['base'];
                $exchangeRateRepository->save($currencyOut,$currencyIn,$exchangeValue);
            }
        }

        $io->success('Exchange Rates Command Executed Successfully');
    }
}