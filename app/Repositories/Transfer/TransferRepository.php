<?php

namespace App\Repositories\Transfer;

use App\Models\Transfer;

class TransferRepository
{
    public function createTransfer(array $data)
    {
        $payer = $data['payer'];
        $payee = $data['payee'];

        return Transfer::create([
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => $data['value'],
        ]);

    }

}
