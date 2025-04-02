<?php

declare(strict_types=1);

namespace App\Validators\Wallet;

use App\Enums\WalletType;
use App\Interfaces\ValidatorInterface;
use RuntimeException;

class ValidatorFactory
{
    public static function create(WalletType $walletType): ValidatorInterface
    {
        if ($walletType == WalletType::USER) {
            return new WalletUserValidator();
        } elseif ($walletType == WalletType::MERCHANT) {
            return new WalletMerchantValidator();
        }


        throw new RuntimeException('Wallet type not supported');
    }

}
