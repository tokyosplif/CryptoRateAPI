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
}