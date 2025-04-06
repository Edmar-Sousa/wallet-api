<?php

namespace App\Interfaces;

use App\Models\Transfer;
use App\Models\Wallet;

interface UseCaseTransferInterface
{

    /**
     * Business rules to cancel a transfer between two wallets
     * 
     * @param int $transferId
     * @return void
     */
    public function cancelTransfer(int $transferId): void;

    /**
     * Business rules to create a transfer between two wallets
     * 
     * @param array{'payer': int, 'payee':int, 'value':float} $transferData
     * @return array{'payer':int, 'payee': int, 'value': float}
     */
    public function transferBetweenWallets(array $transferData): array;

}