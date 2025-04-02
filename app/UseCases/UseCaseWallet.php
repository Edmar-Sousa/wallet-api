<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Enums\WalletType;
use App\Exceptions\WalletNotFoundException;
use App\Models\Wallet;
use App\Repositories\Wallet\WalletRepository;

class UseCaseWallet
{
    public function findWallet(int $walletId)
    {
        $walletRepository = new WalletRepository();
        $wallet = $walletRepository->getWallet($walletId);

        if ($wallet === null) {
            throw new WalletNotFoundException(
                'Error to find wallet with id ' . $walletId,
                'wallet_not_found',
                404,
                ['wallet' => 'Carteira com id informado não foi encontrada']
            );
        }

        return $wallet;
    }

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
