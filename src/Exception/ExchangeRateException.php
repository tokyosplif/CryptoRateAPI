<?php

namespace App\Exception;

use Exception;
use Throwable;

class ExchangeRateException extends Exception
{
    public static function fromApiError(string $message, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Exchange rate API error: %s', $message),
            0,
            $previous
        );
    }

    public static function fromValidationError(string $message, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Validation error: %s', $message),
            0,
            $previous
        );
    }
} 