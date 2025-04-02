<?php

namespace App\Validators\Wallet;

use App\Exceptions\ValidationException;
use App\Interfaces\ValidatorInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class WalletMerchantValidator implements ValidatorInterface
{

    public function validate(array $data): void
    {
        try {
            $validator = v::key('fullname', v::stringType()->notEmpty())
                ->key('email', v::email()->notEmpty())
                ->key('password', v::stringType()->notEmpty())
                ->key('cpfCnpj', v::cnpj()->notEmpty());

            $validator->assert($data);
        } catch (NestedValidationException $err) {
            $messages = $err->getMessages([
                'string' => '{{name}} deve ser uma texto',
                'empty' => '{{name}} não pode ser vazio',
                'email' => '{{name}} deve ser um email valido',
                'cnpj' => '{{name}} deve ser um cnpj valido',
            ]);

            throw new ValidationException(
                'Data to create wallet is invalid',
                'validation_wallet_error',
                400,
                $messages
            );
        }
    }
}
