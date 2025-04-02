<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\CustomException;

class WalletBalanceInsufficientException extends CustomException
{
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'wallet_balance_insufficient',
            403,
            $messages
        );
    }
}
