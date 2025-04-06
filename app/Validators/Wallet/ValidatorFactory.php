<?php

declare(strict_types=1);

namespace App\Validators\Wallet;

use App\Enums\WalletType;
use App\Interfaces\ValidatorInterface;
use RuntimeException;

class ValidatorFactory
{
    /**
     * Factory to create validator class
     * 
     * @param \App\Enums\WalletType $walletType
     * 
     * @throws \RuntimeException
     * @return WalletMerchantValidator|WalletUserValidator
     */
    public static function create(WalletType $walletType): ValidatorInterface
    {

        switch ($walletType) {
            case WalletType::USER: return new WalletUserValidator();
            case WalletType::MERCHANT: return new WalletMerchantValidator();

            default:
                throw new RuntimeException('Wallet type not supported');
        }
    }

}
