<?php


declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\CustomException;

class TransferNotFoundException extends CustomException
{
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'transaction_not_found',
            404,
            $messages
        );
    }

}
