<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Exceptions\WalletNotFoundException;
use App\Enums\WalletType;
use App\Models\Wallet;

interface UseCaseWalletInterface
{

    /**
     * Business rule to find wallet data in database
     * 
     * @param int $walletId
     * 
     * @throws WalletNotFoundException
     * @return Wallet
     */
    public function findWallet(int $walletId): Wallet;

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
    public function createWallet(array $data, WalletType $type): Wallet;

}