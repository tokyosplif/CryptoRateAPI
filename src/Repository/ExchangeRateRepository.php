<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
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

    public function save(ExchangeRate $exchangeRate): void
    {
        $this->entityManager->persist($exchangeRate);
        $this->entityManager->flush();
    }

    public function findLatestRates(): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT e.currencyPair, e.rateUSD, e.rateEUR, e.rateGBP, e.timestamp 
         FROM App\Entity\ExchangeRate e 
         WHERE e.currencyPair IN (:pairs)
         ORDER BY e.timestamp DESC'
        )
            ->setParameter('pairs', ['BTC', 'ETH', 'LTC'])
            ->setMaxResults(3);

        return $query->getResult();
    }

    public function findRatesHistory(string $currencyPair, DateTime $from, DateTime $to): array
    {
        return $this->entityManager->createQuery(
            'SELECT e FROM App\Entity\ExchangeRate e 
         WHERE e.currencyPair = :currencyPair 
         AND e.timestamp BETWEEN :from AND :to 
         ORDER BY e.timestamp ASC'
        )->setParameters([
            'currencyPair' => $currencyPair,
            'from' => $from,
            'to' => $to,
        ])->getResult();
    }
}
