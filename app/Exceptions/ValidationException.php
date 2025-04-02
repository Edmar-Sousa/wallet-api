<?php

namespace App\Exceptions;

use App\Exceptions\CustomException;

class ValidationException extends CustomException
{
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'validation_wallet_error',
            400,
            $messages
        );
    }
}
