<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\CustomException;

class InternalErrorException extends CustomException
{
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'internal_error',
            500,
            $messages
        );
    }

}
