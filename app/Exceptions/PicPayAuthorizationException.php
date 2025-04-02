<?php

namespace App\Exceptions;

use App\Exceptions\CustomException;

class PicPayAuthorizationException extends CustomException
{
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'transaction_not_authorized',
            403,
            $messages
        );
    }

}
