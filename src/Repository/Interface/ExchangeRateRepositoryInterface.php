<?php

namespace App\Repository\Interface;

use App\Entity\ExchangeRate;
use DateTime;

interface ExchangeRateRepositoryInterface
{
    public function save(ExchangeRate $exchangeRate): void;

    public function findLatestRates(): array;

    public function findRatesHistory(string $currencyPair, DateTime $from, DateTime $to): array;
}
