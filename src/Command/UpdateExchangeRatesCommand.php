<?php

namespace App\Command;

use App\Exception\ExchangeRateException;
use App\Service\ExchangeRateService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateExchangeRatesCommand extends Command
{
    protected static $defaultName = 'app:update-exchange-rates';
    private ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        parent::__construct();
        $this->exchangeRateService = $exchangeRateService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Updates the exchange rates from the external API')
            ->setHelp('This command allows you to update the exchange rates from the external API and save them to the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->exchangeRateService->updateExchangeRates();
            $io->success('Exchange rates updated successfully.');
            return Command::SUCCESS;
        } catch (ExchangeRateException $e) {
            $io->error('Failed to update exchange rates: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (Exception $e) {
            $io->error('An unexpected error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}