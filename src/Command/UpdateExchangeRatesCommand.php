<?php

namespace App\Command;

use App\Service\ExchangeRateService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:update-exchange-rates')]
class UpdateExchangeRatesCommand extends Command
{
    private ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        parent::__construct();
        $this->exchangeRateService = $exchangeRateService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->exchangeRateService->updateExchangeRates();
        $output->writeln('Exchange rates updated successfully.');
        return Command::SUCCESS;
    }
}
