<?php

declare(strict_types=1);

namespace App\Repositories\Wallet;

use App\Models\Wallet;

interface WalletRepositoryInterface
{
    function createUserWallet(array $data): Wallet;
    function createMerchantWallet(array $data): Wallet;
    function getWallet(int $id): Wallet|null;
    function getWalletForUpdate(int $id): Wallet|null;
    function debtWallet(Wallet $wallet, int $value): void;
    function creditWallet(Wallet $wallet, int $value): void;
}