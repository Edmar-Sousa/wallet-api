<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Transfer;

interface TransferRepositoryInterface
{

    /**
     * Deleta a transfer in database
     * 
     * @param int $transferId
     * @return void
     */
    public function deleteTransferWithId(int $transferId): void;

    /**
     * Return a transfer with id
     * 
     * @param int $transferId
     * @throws \App\Exceptions\TransferNotFoundException
     * 
     * @return Transfer
     */
    public function getTransferWithId(int $transferId): Transfer;

    /**
     * @param array{
     *    'payer': \App\Models\Wallet, 
     *    'payee': \App\Models\Wallet, 
     *    'value': int
     * } $data
     * 
     * @return Transfer
     */
    public function createTransfer(array $data): Transfer;

}