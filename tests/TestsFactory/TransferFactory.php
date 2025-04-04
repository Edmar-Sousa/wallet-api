<?php

namespace Tests\TestsFactory;

use App\Models\Transfer;

class TransferFactory
{

    public static function createTransfer(int $payerId, int $payeeId, int $amount): Transfer
    {
        return Transfer::create([
            'payer' => $payerId,
            'payee' => $payeeId,
            'value' => $amount,
        ]);
    }

}