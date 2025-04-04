<?php

namespace Tests\TestsFactory;

use App\Enums\WalletType;
use App\Models\Wallet;
use Tests\Fixtures\UserFixtures;

class WalletFactory
{

    public static function createWalletInDatabaseWithoutBalance(WalletType $type = WalletType::USER, int $balance = 0)
    {
        $wallet = array_merge(UserFixtures::createUser(), [ 'type' => $type, 'balance' => $balance ]);
        return Wallet::create($wallet);
    }

}