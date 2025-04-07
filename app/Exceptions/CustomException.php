<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class CustomException extends RuntimeException
{
    /**
     * @var string
     */
    private string $errorCode;

    /**
     * @var array<string, string>
     */
    private array $errorMessage;

    /**
     * Base exception to create a response to API
     *
     * @param string $log
     * @param string $code
     * @param int $statusCode
     *
     * @param array<string, string> $messages
     */
    public function __construct(string $log, string $code, int $statusCode, array $messages = [])
    {
        parent::__construct($log, $statusCode);

        $this->errorCode = $code;
        $this->errorMessage = $messages;
    }


    /**
     * returns an array representing the structure of the API response json
     *
     * @return array{'code': string, 'errors': array<string, string>, 'status': string}
     */
    final public function getErrorObject(): array
    {
        return [
            'status' => $this->code,
            'code' => $this->errorCode,
            'errors' => $this->errorMessage,
        ];
    }
}
