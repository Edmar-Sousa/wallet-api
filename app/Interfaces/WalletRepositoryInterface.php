<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Exceptions\CreateWalletException;
use App\Models\Wallet;

interface WalletRepositoryInterface
{
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
    public function createUserWallet(array $data): Wallet;

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
    public function createMerchantWallet(array $data): Wallet;


    /**
     * Method to get wallet with id from database
     * 
     * @param int $id
     * @return Wallet|null
     */
    public function getWallet(int $id): Wallet|null;

    /**
     * Method to get wallet with id from database to update.
     * This method lock the register to prevent race condition
     * 
     * @param int $id
     * @return Wallet|null
     */
    public function getWalletForUpdate(int $id): Wallet|null;

    /**
     * This method debt an value from wallet
     * 
     * @param Wallet $wallet
     * @param int $value
     * 
     * @return void
     */
    public function debtWallet(Wallet $wallet, int $value): void;

    /**
     * This method credit an value to wallet
     * 
     * @param Wallet $wallet
     * @param int $value
     * 
     * @return void
     */
    public function creditWallet(Wallet $wallet, int $value): void;
}