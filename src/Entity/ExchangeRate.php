<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string")]
    private string $currency_pair;

    #[ORM\Column(type: Types::JSON)]
    private array $rates = [];

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $timestamp;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrencyPair(): string
    {
        return $this->currency_pair;
    }

    public function setCurrencyPair(string $currencyPair): self
    {
        $this->currency_pair = $currencyPair;
        return $this;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public function setRates(array $rates): self
    {
        $this->rates = $rates;
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

