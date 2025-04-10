<?php

namespace App\Validators\Wallet;

use App\Exceptions\ValidationException;
use App\Interfaces\ValidatorInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * Apply rules to validate data to store wallet
 *
 * @implements ValidatorInterface<array{
 *     fullname: string,
 *     cpfCnpj: string,
 *     email: string,
 *     password: string
 * }>
 */
class WalletMerchantValidator implements ValidatorInterface
{
    /**
     * Apply rules to validate data to store wallet
     *
     * @return void
     */
    public function validate(array $data): void
    {
        try {
            $validator = v::key(
                'fullname',
                v::stringType()->notEmpty()->setTemplate('O campo nome completo deve ser um texto')
            )
                ->key(
                    'email',
                    v::email()->notEmpty()->setTemplate('O campo deve ser um e-mail válido')
                )
                ->key(
                    'password',
                    v::stringType()->notEmpty()->setTemplate('O campo senha deve ser um texto')
                )
                ->key(
                    'cpfCnpj',
                    v::cnpj()->notEmpty()->setTemplate('O campo deve ser um CNPJ válido')
                );

            $validator->assert($data);
        } catch (NestedValidationException $err) {
            $messages = $err->getMessages();

            throw new ValidationException(
                'Data to create user wallet is invalid',
                $messages
            );
        }
    }
}
