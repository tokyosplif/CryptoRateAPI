<?php

namespace App\Enum;

enum FiatCurrency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getAllSymbols(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function isValidCurrency(string $currency): bool
    {
        return in_array($currency, self::getAllValues(), true);
    }
}
