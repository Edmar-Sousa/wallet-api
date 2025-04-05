<?php

namespace App\Interfaces;

use App\Models\Transfer;
use App\Models\Wallet;

interface UseCaseTransferInterface
{

    function cancelTransfer(int $transferId): void;
    function transferBetweenWallets(array $transferData): Transfer;

}