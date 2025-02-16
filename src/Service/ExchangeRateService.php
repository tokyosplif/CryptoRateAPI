<?php

namespace App\Service;

use App\Entity\ExchangeRate;
use App\Repository\Interface\ExchangeRateRepositoryInterface;
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private ExchangeRateRepositoryInterface $exchangeRateRepository;
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private const API_URL = 'https://api.coingecko.com/api/v3/simple/price';
    private array $cryptoCurrencies = ['bitcoin', 'ethereum', 'litecoin'];
    private array $fiatCurrencies = ['usd', 'eur', 'gbp'];

    public function __construct(
        ExchangeRateRepositoryInterface $exchangeRateRepository,
        HttpClientInterface $httpClient,
        LoggerInterface $logger
    ) {
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function updateExchangeRates(): void
    {
        $queryParams = [
            'ids' => implode(',', $this->cryptoCurrencies),
            'vs_currencies' => implode(',', $this->fiatCurrencies),
        ];

        try {
            $response = $this->httpClient->request('GET', self::API_URL, ['query' => $queryParams]);

            $data = $response->toArray();

            foreach ($this->cryptoCurrencies as $crypto) {
                $exchangeRate = new ExchangeRate();
                $exchangeRate->setCurrencyPair(strtoupper($crypto));
                $exchangeRate->setRateUSD($data[$crypto]['usd'] ?? 0);
                $exchangeRate->setRateEUR($data[$crypto]['eur'] ?? 0);
                $exchangeRate->setRateGBP($data[$crypto]['gbp'] ?? 0);
                $exchangeRate->setTimestamp(new DateTime());

                $this->exchangeRateRepository->save($exchangeRate);
            }
        } catch (Exception $e) {
            $this->logger->error('Error updating exchange rates: ' . $e->getMessage());
        }
    }
}

/* Настройка Cron Job для периодического выполнения:

    Создание команды Symfony:
    Для того чтобы метод updateExchangeRates выполнялся регулярно, была создана команда Symfony.
    Подключение к Cron Job не настроено.