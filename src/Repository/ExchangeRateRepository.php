<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
use App\Enum\CryptoCurrency;
use App\Repository\Interface\ExchangeRateRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(ExchangeRate $rate): void
    {
        $this->entityManager->persist($rate);
        $this->entityManager->flush();
    }

    public function findLatestRates(): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT e
             FROM App\Entity\ExchangeRate e
             WHERE e.currency_pair IN (:pairs)
             ORDER BY e.timestamp DESC'
        )
            ->setParameter('pairs', CryptoCurrency::getAllSymbols())
            ->setMaxResults(count(CryptoCurrency::cases()));

        return $query->getResult();
    }

    public function findLatestRateBySymbol(string $symbol): ?ExchangeRate
    {
        return $this->entityManager->createQuery(
            'SELECT e FROM App\Entity\ExchangeRate e
             WHERE e.currency_pair = :symbol
             ORDER BY e.timestamp DESC'
        )
            ->setParameter('symbol', strtoupper($symbol))
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findRatesHistory(string $currency_pair, DateTime $from, DateTime $to): array
    {
        return $this->entityManager->createQuery(
            'SELECT e FROM App\Entity\ExchangeRate e
             WHERE e.currency_pair = :currencyPair
             AND e.timestamp BETWEEN :from AND :to
             ORDER BY e.timestamp ASC'
            )

            ->setParameter('currencyPair', strtoupper($currency_pair))
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getResult();
    }
}
