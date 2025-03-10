<?php

namespace App\Exception;

use Exception;
use Throwable;

class ExchangeRateException extends Exception
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromApiError(string $message, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Exchange rate API error: %s', $message),
            $previous?->getCode() ?? 0,
            $previous
        );
    }

    public static function fromValidationError(string $message, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Validation error: %s', $message),
            $previous?->getCode() ?? 0,
            $previous
        );
    }
}
