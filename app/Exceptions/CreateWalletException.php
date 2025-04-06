<?php

declare(strict_types=1);

namespace App\Exceptions;

class CreateWalletException extends CustomException
{
    /**
     * Create an internal error exception
     * 
     * @param string $log
     * @param array<string, string> $messages
     */
    public function __construct(string $log, array $messages = [])
    {
        parent::__construct(
            $log,
            'error_create_wallet',
            400,
            $messages
        );
    }

}
