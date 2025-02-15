<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;
    #[ORM\Column(type: "string")]
    private string $currencyPair;
    #[ORM\Column(type: "decimal", precision: 15, scale: 8)]
    private float $rateUSD;
    #[ORM\Column(type: "decimal", precision: 15, scale: 8)]
    private float $rateEUR;
    #[ORM\Column(type: "decimal", precision: 15, scale: 8)]
    private float $rateGBP;
    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $timestamp;

    public function getRateGBP(): float
    {
        return $this->rateGBP;
    }

    public function setRateGBP(float $rateGBP): self
    {
        $this->rateGBP = $rateGBP;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrencyPair(): string
    {
        return $this->currencyPair;
    }

    public function setCurrencyPair(string $currencyPair): self
    {
        $this->currencyPair = $currencyPair;
        return $this;
    }

    public function getRateUSD(): float
    {
        return $this->rateUSD;
    }

    public function setRateUSD(float $rateUSD): self
    {
        $this->rateUSD = $rateUSD;
        return $this;
    }

    public function getRateEUR(): float
    {
        return $this->rateEUR;
    }

    public function setRateEUR(float $rateEUR): self
    {
        $this->rateEUR = $rateEUR;
        return $this;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}
