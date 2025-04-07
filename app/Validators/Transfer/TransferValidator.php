<?php

declare(strict_types=1);

namespace App\Validators\Transfer;

use App\Exceptions\ValidationException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use App\Interfaces\ValidatorInterface;

/**
 * Apply rules to validate data to store transfer
 * 
 * @implements ValidatorInterface<array{
 *   "payer": int, 
 *   "payee": int, 
 *   "value": float 
 * }>
 */
class TransferValidator implements ValidatorInterface
{

    /**
     * Apply rules to validate data
     * 
     * @return void
     */
    public function validate(array $data): void
    {
        try {
            $validator = v::key(
                'value',
                v::numericVal()->notEmpty()->min(0.01)->setTemplate('O campo valor deve ser um numero e no minimo 0.01')
            )->key(
                'payer',
                v::number()->notEmpty()->setTemplate('O campo deve ser o id da carteira do pagador')
            )
            ->key(
                'payee',
                v::number()->notEmpty()->setTemplate('O campo deve ser o id da carteira do beneficiario')
            );

            $validator->validate($data);
        }

        catch (NestedValidationException $err) {
            $messages = $err->getMessages();

            throw new ValidationException(
                'Data to create a transfer is invalid',
                $messages
            );
        }
    }

}
