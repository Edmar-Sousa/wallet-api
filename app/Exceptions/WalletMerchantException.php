<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\CustomException;

class WalletMerchantException extends CustomException
{
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'wallet_not_allowed_transfer',
            422,
            $messages
        );
    }
}
