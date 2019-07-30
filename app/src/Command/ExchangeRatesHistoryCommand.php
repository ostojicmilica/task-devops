<?php

namespace App\Command;

use App\DocumentManager\ExchangeRatesHistory;
use App\Helper\ExchangeRateHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class ExchangeRatesHistoryCommand extends Command
{
    const DATEFROM = '20190707';
    const DATETO = '20190708';
    protected static $defaultName = 'ExchangeRatesHistory';
    private $dm;
    private $exchangeRateHelper;

    /**
     * ExchangeRatesHistoryCommand constructor.
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

        $rates = $this->exchangeRateHelper->getAllExchangeRatesByDates(self::DATEFROM, self::DATETO);
        $exchangeRateHistoryRepository = $this->dm->getRepository(ExchangeRatesHistory::class);
        foreach ($rates as $currencyRate) {
            foreach ($currencyRate['rates'] as $rateDate => $ratesByDate) {
                foreach ($ratesByDate as $rateName => $exchangeValue) {
                    $exchangeRateHistoryRepository->save($rateDate, $currencyRate["base"],$rateName, $exchangeValue);
                }
            }
        }

        $io->success('Exchange Rates History Command Executed Successfully');

    }
}