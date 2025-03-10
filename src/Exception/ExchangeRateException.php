<?php

namespace App\Exception;

use Exception;
use Throwable;
use Psr\Log\LoggerInterface;

class ExchangeRateException extends Exception
{
    private LoggerInterface $logger;

    public function __construct(string $message, LoggerInterface $logger, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->logger = $logger;
        $this->logError();
    }

    private function logError(): void
    {
        $this->logger->error('ExchangeRateException: ' . $this->getMessage(), [
            'stack_trace' => $this->getTraceAsString()
        ]);
    }

    public static function fromApiError(string $message, LoggerInterface $logger, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Exchange rate API error: %s', $message),
            $logger,
            $previous?->getCode() ?? 0,
            $previous
        );
    }

    public static function fromValidationError(string $message, LoggerInterface $logger, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Validation error: %s', $message),
            $logger,
            $previous?->getCode() ?? 0,
            $previous
        );
    }
}
