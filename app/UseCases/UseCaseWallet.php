<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Enums\WalletType;
use App\Models\Wallet;
use App\Repositories\Wallet\WalletRepository;

class UseCaseWallet
{
    public function createWallet(array $data, WalletType $type): Wallet
    {
        $walletRepository = new WalletRepository();

        if ($type == WalletType::MERCHANT) {
            $wallet = $walletRepository->createMerchantWallet($data);
        } else {
            $wallet = $walletRepository->createUserWallet($data);
        }

        return $wallet;
    }

}
