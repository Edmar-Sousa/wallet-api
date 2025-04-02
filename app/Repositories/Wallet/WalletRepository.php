<?php

declare(strict_types=1);

namespace App\Repositories\Wallet;

use App\Enums\WalletType;
use App\Models\Wallet;

class WalletRepository
{
    private function createWallet(array $data, WalletType $type, int $balance = 0): Wallet
    {
        return Wallet::create([
            'fullname' => $data['fullname'],
            'cpfCnpj'  => $data['cpfCnpj'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 13]),

            // balance to tests
            'balance'  => $balance,
            'type'     => $type,
        ]);
    }

    public function createUserWallet(array $data): Wallet
    {
        return $this->createWallet($data, WalletType::USER, 1000000);
    }

    public function createMerchantWallet(array $data): Wallet
    {
        return $this->createWallet($data, WalletType::MERCHANT, 1000000);
    }


    public function getWallet(int $id): Wallet|null
    {
        $wallet = Wallet::where('id', $id)
            ->lockForUpdate()
            ->first();

        $wallet->type = WalletType::from($wallet->type);

        return $wallet;
    }


    public function debtWallet(Wallet $wallet, int $value): void
    {
        Wallet::where('id', $wallet->id)
            ->update([
                'balance' => $wallet->balance - $value
            ]);
    }

    public function creditWallet(Wallet $wallet, int $value): void
    {
        Wallet::where('id', $wallet->id)
            ->update([
                'balance' => $wallet->balance + $value
            ]);
    }
}
