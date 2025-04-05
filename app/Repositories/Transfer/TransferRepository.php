<?php

declare(strict_types=1);

namespace App\Repositories\Transfer;

use App\Exceptions\TransferNotFoundException;
use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;

class TransferRepository implements TransferRepositoryInterface
{

    public function deleteTransferWithId(int $transferId)
    {
        Transfer::where('id', $transferId)
            ->delete();
    }


    public function getTransferWithId(int $transferId)
    {
        $transfer = Transfer::where('id', $transferId)
            ->with([
                'walletPayer',
                'walletPayee',
            ])
            ->first();

        if (!$transfer) {
            throw new TransferNotFoundException(
                'Error to find transfer with id: ' . $transferId,
                [ 'transfer' => 'Transferencia com o não foi encontrada ou não existe' ]
            );
        }

        return $transfer;
    }

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
