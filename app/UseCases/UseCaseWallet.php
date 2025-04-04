<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Enums\WalletType;
use App\Exceptions\WalletNotFoundException;
use App\Models\Wallet;
use App\Repositories\Wallet\WalletRepositoryInterface;

class UseCaseWallet
{

    private WalletRepositoryInterface $walletRepository;


    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }


    public function findWallet(string $walletId)
    {
        $wallet = $this->walletRepository->getWallet((int) $walletId);

        if ($wallet === null) {
            throw new WalletNotFoundException(
                'Error to find wallet with id ' . $walletId,
                ['wallet' => 'Carteira com id informado não foi encontrada']
            );
        }

        $wallet->balance = floatval(intval($wallet->balance) / 100);

        return $wallet;
    }

    public function createWallet(array $data, WalletType $type): Wallet
    {
        if ($type == WalletType::MERCHANT) {
            $wallet = $this->walletRepository->createMerchantWallet($data);
        } else {
            $wallet = $this->walletRepository->createUserWallet($data);
        }

        return $wallet;
    }

}
