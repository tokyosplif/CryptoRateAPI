<?php

namespace App\Service;

use App\DTO\ExchangeRateDTO;
use App\Entity\ExchangeRate;
use App\Enum\CryptoCurrency;
use App\Enum\FiatCurrency;
use App\Exception\ExchangeRateException;
use App\Repository\Interface\ExchangeRateRepositoryInterface;
use DateTime;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class ExchangeRateService
{
    private const API_URL = 'https://api.coingecko.com/api/v3/simple/price';

    public function __construct(
        private readonly ExchangeRateRepositoryInterface $exchangeRateRepository,
        private readonly HttpClientInterface $httpClient
    ) {
    }

    /**
     * @throws ExchangeRateException
     */
    public function updateExchangeRates(): void
    {
        try {
            $rates = $this->fetchExchangeRates();
            foreach ($rates as $rateDTO) {
                $this->saveExchangeRate($rateDTO);
            }
        } catch (Throwable $e) {
            throw ExchangeRateException::fromApiError($e->getMessage(), $e);
        }
    }

    /**
     * @return ExchangeRateDTO[]
     * @throws ExchangeRateException
     */
    private function fetchExchangeRates(): array
    {
        try {
            $queryParams = [
                'ids' => implode(',', CryptoCurrency::getAllValues()),
                'vs_currencies' => implode(',', FiatCurrency::getAllValues()),
            ];

            $response = $this->httpClient->request('GET', self::API_URL, ['query' => $queryParams]);
            $data = $response->toArray();

            return $this->processApiResponse($data);
        } catch (ExceptionInterface $e) {
            throw ExchangeRateException::fromApiError('API request failed', $e);
        }
    }

    /**
     * @throws ExchangeRateException
     */
    private function processApiResponse(array $data): array
    {
        $result = [];
        $timestamp = new DateTime();

        foreach (CryptoCurrency::cases() as $crypto) {
            if (!isset($data[$crypto->value])) {
                throw ExchangeRateException::fromValidationError(
                    sprintf('Missing data for cryptocurrency: %s', $crypto->value)
                );
            }

            $rates = [];
            foreach (FiatCurrency::cases() as $fiat) {
                if (!isset($data[$crypto->value][$fiat->value])) {
                    throw ExchangeRateException::fromValidationError(
                        sprintf('Missing %s rate for %s', $fiat->value, $crypto->value)
                    );
                }
                $rates[$fiat->value] = $data[$crypto->value][$fiat->value];
            }

            $currencyPair = $crypto->value;
            $result[] = new ExchangeRateDTO($currencyPair, $rates, $timestamp);

        }

        return $result;
    }


    /**
     * @throws ExchangeRateException
     */
    private function saveExchangeRate(ExchangeRateDTO $rateDTO): void
    {
        $exchangeRate = new ExchangeRate();
        $exchangeRate->setCurrencyPair($rateDTO->getCurrencyPair()); // Используем getCurrencyPair()

        $exchangeRate->setRates($rateDTO->getRates());
        $exchangeRate->setTimestamp($rateDTO->getTimestamp());

        $this->exchangeRateRepository->save($exchangeRate);
    }


    /**
     * Получить последние курсы валют
     * @throws ExchangeRateException
     */
    public function getLatestRates(): array
    {
        try {
            $rates = $this->exchangeRateRepository->findLatestRates();

            return array_map(function ($rate) {
                $formattedRate = new ExchangeRateDTO(
                    $rate->getCurrencyPair(),
                    $rate->getRates(),
                    $rate->getTimestamp()
                );

                $formattedData = [
                    'currencyPair' => $formattedRate->getCurrencyPair(),
                    'timestamp' => $formattedRate->getTimestamp()->format('Y-m-d H:i:s'),
                ];

                foreach (FiatCurrency::cases() as $fiat) {
                    $formattedData[$fiat->value] = $formattedRate->getRates()[$fiat->value] ?? null;
                }

                return $formattedData;
            }, $rates);
        } catch (Exception $e) {
            throw ExchangeRateException::fromApiError('Failed to fetch latest rates', $e);
        }
    }

    /**
     * @throws ExchangeRateException
     */
    public function getRatesHistory(string $currency, DateTime $fromDate, DateTime $toDate): array
    {
        try {
            $history = $this->exchangeRateRepository->findRatesHistory($currency, $fromDate, $toDate);

            return array_map(function ($rate) {
                $formattedRate = new ExchangeRateDTO(
                    $rate->getCurrencyPair(),
                    $rate->getRates(),
                    $rate->getTimestamp()
                );

                $formattedData = [
                    'date' => $formattedRate->getTimestamp()->format('Y-m-d H:i:s'),
                ];

                foreach (FiatCurrency::cases() as $fiat) {
                    $formattedData[$fiat->value] = $formattedRate->getRates()[$fiat->value] ?? null;
                }

                return $formattedData;
            }, $history);
        } catch (Exception $e) {
            throw ExchangeRateException::fromApiError('Failed to fetch rates history', $e);
        }
    }
}
/*   Создание команды Symfony:
     Для того чтобы метод updateExchangeRates выполнялся регулярно,
     была создана команда Symfony. Эта команда будет запускать логику обновления курсов валют.

     Использование Cron Job или Symfony Scheduler:
     Периодическое выполнение команды можно настроить как через Cron Job, так и через Symfony Scheduler
     для регулярного выполнения задачи.*/