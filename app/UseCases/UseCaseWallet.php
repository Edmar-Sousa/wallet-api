<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Enums\WalletType;
use App\Exceptions\WalletNotFoundException;
use App\Interfaces\UseCaseWalletInterface;
use App\Interfaces\WalletRepositoryInterface;
use App\Models\Wallet;

class UseCaseWallet implements UseCaseWalletInterface
{
    private WalletRepositoryInterface $walletRepository;


    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * Business rule to find wallet data in database
     *
     * @param int $walletId
     *
     * @throws WalletNotFoundException
     * @return Wallet
     */
    public function findWallet(int $walletId): Wallet
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

    /**
     * Business rules to create a wallet in database
     *
     * @param array{
     *  "fullname": string,
     *  "cpfCnpj": string,
     *  "email": string,
     *  "password": string
     * } $data
     * @param \App\Enums\WalletType $type
     *
     * @return Wallet
     */
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
