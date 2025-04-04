<?php

declare(strict_types=1);

namespace App\Repositories\Wallet;

use App\Enums\WalletType;
use App\Exceptions\CreateWalletException;
use App\Models\Wallet;

class WalletRepository
{
    private function hasWalletWithEmailOrCpfCnpj(string $email, string $cpfCnpj)
    {
        $wallet = Wallet::where('email', $email)
            ->orWhere('cpfCnpj', $cpfCnpj)
            ->first();

        return $wallet !== null;
    }

    private function createWallet(array $data, WalletType $type): Wallet
    {
        return Wallet::create([
            'fullname' => $data['fullname'],
            'cpfCnpj'  => $data['cpfCnpj'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 13]),

            'type'     => $type,
        ]);
    }

    public function createUserWallet(array $data): Wallet
    {
        if ($this->hasWalletWithEmailOrCpfCnpj($data['email'], $data['cpfCnpj'])) {
            throw new CreateWalletException(
                'Error to create user wallet',
                [ 'message' => 'Ja existe uma carteira com o email ou cpf informado' ]
            );
        }

        return $this->createWallet($data, WalletType::USER);
    }

    public function createMerchantWallet(array $data): Wallet
    {
        if ($this->hasWalletWithEmailOrCpfCnpj($data['email'], $data['cpfCnpj'])) {
            throw new CreateWalletException(
                'Error to create merchant wallet',
                [ 'message' => 'Ja existe uma carteira com o email ou cnpj informado' ]
            );
        }

        return $this->createWallet($data, WalletType::MERCHANT, 1000000);
    }


    public function getWallet(int $id): Wallet|null
    {
        $wallet = Wallet::where('id', $id)
            ->first();

        if ($wallet !== null) {
            $wallet->type = WalletType::from($wallet->type);
        }

        return $wallet;
    }

    public function getWalletForUpdate(int $id): Wallet|null
    {
        $wallet = Wallet::where('id', $id)
            ->lockForUpdate()
            ->first();

        if ($wallet !== null) {
            $wallet->type = WalletType::from($wallet->type);
        }

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
