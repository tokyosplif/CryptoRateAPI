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
}