<?php


namespace OpenAPI\Server\Exception;


use Exception;
use InvalidArgumentException;
use Throwable;

class ApiErrorException extends Exception
{
    private $errorType;

    public function __construct(string $message, string $errorType, int $httpStatus = 500, Throwable $previous = null)
    {
        if (empty($errorType)) {
            throw new InvalidArgumentException("Error type cannot be empty");
        }
        $this->errorType = $errorType;

        parent::__construct($message, $httpStatus, $previous);
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }

    public function getHttpStatus(): int
    {
        return $this->getCode();
    }
}