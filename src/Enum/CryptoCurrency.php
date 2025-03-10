<?php

namespace App\Enum;

enum CryptoCurrency: string
{
    case BTC = 'BTC';
    case ETH = 'ETH';
    case LTC = 'LTC';

    public function getSymbol(): string
    {
        return match($this) {
            self::BTC => 'BTC',
            self::ETH => 'ETH',
            self::LTC => 'LTC',
        };
    }

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getAllSymbols(): array
    {
        return array_map(fn($case) => $case->getSymbol(), self::cases());
    }

    public static function isValidCurrency(string $currency): bool
    {
        return in_array($currency, array_map(fn($c) => $c->value, self::cases()), true);
    }
}