<?php

declare(strict_types=1);

namespace App\Repositories\Transfer;

use App\Exceptions\TransferNotFoundException;
use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;
use App\Models\Wallet;

class TransferRepository implements TransferRepositoryInterface
{
    /**
     * Deleta a transfer in database
     *
     * @param int $transferId
     * @return void
     */
    public function deleteTransferWithId(int $transferId): void
    {
        /** @phpstan-ignore-next-line */
        Transfer::where('id', $transferId)
            ->delete();
    }


    /**
     * Return a transfer with id
     *
     * @param int $transferId
     * @throws \App\Exceptions\TransferNotFoundException
     *
     * @return Transfer
     */
    public function getTransferWithId(int $transferId): Transfer
    {
        /** @phpstan-ignore-next-line */
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

    /**
     * Create transfer in database
     *
     * @param array{
     *    'payer': \App\Models\Wallet,
     *    'payee': \App\Models\Wallet,
     *    'value': int
     * } $data
     *
     * @return Transfer
     */
    public function createTransfer(array $data): Transfer
    {
        /** @var Wallet */
        $payer = $data['payer'];
        $payee = $data['payee'];

        /** @phpstan-ignore-next-line */
        return Transfer::create([
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => $data['value'],
        ]);

    }

}
