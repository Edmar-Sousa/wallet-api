<?php

namespace App\Exceptions;

use App\Exceptions\CustomException;

class TransferException extends CustomException
{
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'transfer_failed',
            403,
            $messages
        );
    }
}
