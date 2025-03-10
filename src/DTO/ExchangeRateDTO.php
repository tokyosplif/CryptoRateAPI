<?php

namespace App\DTO;

use DateTimeInterface;

class ExchangeRateDTO
{
    private string $currency_pair;
    private array $rates;
    private DateTimeInterface $timestamp;

    public function __construct(string $currency_pair, array $rates, DateTimeInterface $timestamp)
    {
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
}