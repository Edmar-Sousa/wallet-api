<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Enums\WalletType;
use App\Models\Wallet;

interface UseCaseWalletInterface
{

    function findWallet(string $walletId);
    function createWallet(array $data, WalletType $type): Wallet;

}