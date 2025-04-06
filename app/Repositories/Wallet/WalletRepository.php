<?php

declare(strict_types=1);

namespace App\Repositories\Wallet;

use App\Enums\WalletType;
use App\Exceptions\CreateWalletException;
use App\Interfaces\WalletRepositoryInterface;
use App\Models\Wallet;

class WalletRepository implements WalletRepositoryInterface
{
    /**
     * This method check if already exists an register with email
     * or cpfCnpj
     * 
     * @param string $email
     * @param string $cpfCnpj
     * 
     * @return bool
     */
    private function hasWalletWithEmailOrCpfCnpj(string $email, string $cpfCnpj): bool
    {
        /** @phpstan-ignore-next-line */
        $wallet = Wallet::where('email', $email)
            ->orWhere('cpfCnpj', $cpfCnpj)
            ->first();

        return $wallet !== null;
    }

    /**
     * Store wallet in table
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
    private function createWallet(array $data, WalletType $type): Wallet
    {
        /** @phpstan-ignore-next-line */
        return Wallet::create([
            'fullname' => $data['fullname'],
            'cpfCnpj'  => $data['cpfCnpj'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 13]),

            'type'     => $type,
        ]);
    }

    /**
     * Method to store wallet user in database
     * 
     * @param array{
     *  "fullname": string, 
     *  "cpfCnpj": string, 
     *  "email": string, 
     *  "password": string 
     * } $data
     * 
     * @throws CreateWalletException
     * @return Wallet
     */
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

    /**
     * Method to store wallet merchant in database
     * 
     * @param array{
     *  "fullname": string, 
     *  "cpfCnpj": string, 
     *  "email": string, 
     *  "password": string 
     * } $data
     * 
     * @throws CreateWalletException
     * @return Wallet
     */
    public function createMerchantWallet(array $data): Wallet
    {
        if ($this->hasWalletWithEmailOrCpfCnpj($data['email'], $data['cpfCnpj'])) {
            throw new CreateWalletException(
                'Error to create merchant wallet',
                [ 'message' => 'Ja existe uma carteira com o email ou cnpj informado' ]
            );
        }

        return $this->createWallet($data, WalletType::MERCHANT);
    }


    /**
     * Method to get wallet with id from database
     * 
     * @param int $id
     * @return Wallet|null
     */
    public function getWallet(int $id): Wallet|null
    {
        /** @phpstan-ignore-next-line */
        $wallet = Wallet::where('id', $id)
            ->first();

        return $wallet;
    }


    /**
     * Method to get wallet with id from database to update.
     * This method lock the register to prevent race condition
     * 
     * @param int $id
     * @return Wallet|null
     */
    public function getWalletForUpdate(int $id): Wallet|null
    {
        /** @phpstan-ignore-next-line */
        $wallet = Wallet::where('id', $id)
            ->lockForUpdate()
            ->first();

        return $wallet;
    }


    /**
     * This method debt an value from wallet
     * 
     * @param Wallet $wallet
     * @param int $value
     * 
     * @return void
     */
    public function debtWallet(Wallet $wallet, int $value): void
    {
        /** @phpstan-ignore-next-line */
        Wallet::where('id', $wallet->id)
            ->update([
                'balance' => $wallet->balance - $value
            ]);
    }

    /**
     * This method credit an value to wallet
     * 
     * @param Wallet $wallet
     * @param int $value
     * 
     * @return void
     */
    public function creditWallet(Wallet $wallet, int $value): void
    {
        /** @phpstan-ignore-next-line */
        Wallet::where('id', $wallet->id)
            ->update([
                'balance' => $wallet->balance + $value
            ]);
    }
}
