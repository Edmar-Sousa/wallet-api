<?php declare(strict_types=1);

namespace App\Validators;

use App\Enums\WalletType;
use App\Interfaces\ValidatorInterface;
use http\Exception\RuntimeException;

class ValidatorFactory
{

    public static function create(WalletType $walletType): ValidatorInterface
    {
        if ($walletType == WalletType::USER)
            return new WalletUserValidator();

        else if ($walletType == WalletType::MERCHANT)
            return new WalletMerchantValidator();


        throw new RuntimeException('Wallet type not supported');
    }

}