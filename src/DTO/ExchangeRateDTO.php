<?php

namespace App\DTO;

use App\Exception\ExchangeRateException;
use DateTimeInterface;

class ExchangeRateDTO
{
    private string $currency_pair;
    private array $rates;
    private DateTimeInterface $timestamp;

    /**
     * @throws ExchangeRateException
     */
    public function __construct(string $currency_pair, array $rates, DateTimeInterface $timestamp)
    {
        $this->validateRates($rates);
        $this->currency_pair = $currency_pair;
        $this->rates = $rates;
        $this->timestamp = $timestamp;
    }

    public function getCurrencyPair(): string
    {
        return $this->currency_pair;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @throws ExchangeRateException
     */
    private function validateRates(array $rates): void
    {
        foreach ($rates as $currency => $rate) {
            if (!is_numeric($rate) || $rate < 0) {
                throw ExchangeRateException::fromValidationError(
                    sprintf('Invalid rate value for currency %s: %s', $currency, $rate)
                );
            }
        }
    }

    /**
     * @throws ExchangeRateException
     */
    public function getRate(string $currency): float
    {
        if (!isset($this->rates[$currency])) {
            throw ExchangeRateException::fromValidationError(
                sprintf('Rate for currency %s not found', $currency)
            );
        }
        return $this->rates[$currency];
    }
}
