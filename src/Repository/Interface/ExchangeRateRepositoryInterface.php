<?php

namespace App\Repository\Interface;

use App\Entity\ExchangeRate;
use DateTime;

interface ExchangeRateRepositoryInterface
{
    public function save(ExchangeRate $rate): void;

    /**
     * @return ExchangeRate[]
     */
    public function findLatestRates(): array;

    public function findLatestRateBySymbol(string $symbol): ?ExchangeRate;

    public function findRatesHistory(string $currency_pair, DateTime $from, DateTime $to): array;
}
