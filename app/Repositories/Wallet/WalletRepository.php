<?php declare(strict_types=1);

namespace App\Repositories\Wallet;

use App\Enums\WalletType;
use App\Models\Wallet;

class WalletRepository
{

    public function createUserWallet(array $data): Wallet
    {
        $wallet = Wallet::create([
            'fullname' => $data['fullname'],
            'cpfCnpj'  => $data['cpfCnpj'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 13]),

            // balance to tests
            'balance'  => 1000000,
            'type'     => WalletType::USER,
        ]);

        return $wallet;
    }

}