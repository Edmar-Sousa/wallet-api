<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class CustomException extends RuntimeException
{
    private string $errorCode;
    private array $errorMessage;

    final public function __construct(string $log, string $code, int $statusCode, array $messages = [])
    {
        parent::__construct($log, $statusCode);

        $this->errorCode = $code;
        $this->errorMessage = $messages;
    }


    final public function getErrorObject(): array
    {
        return [
            'status' => $this->code,
            'code' => $this->errorCode,
            'errors' => $this->errorMessage,
        ];
    }
}
